<?php

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\UserMoneyLog;
use App\Models\Users\UserCart;
use App\Models\Users\Users;
use App\Models\Setting;
use App\Workers\TotalTeamPointDayWorker;
use AppView\Helpers\BaoKimAPI;
use VatGia\Queue\Facade\Queue;

//Công thức tính hoa hồng
//Vẫn giữ nguyên công thức là tiền thu về = giá gốc - tổng chiết khấu (Hoa hồng + chính sách giá = tổng chiết khấu)
//Nhưng giá gốc để bắt đầu tính tiền hoa hồng là giá của mức bắt đầu giá bán buôn

$vars = false;
$v_off_gia_sp_lever = true; //30/6/2023. Luon lay muc gia chiet khau nho nhat
$user = Users::findByID(input('user_id'));
if (empty($user)) {
    throw new RuntimeException("Người dùng không tồn tại", 400);
}
//Check địa chỉ
$address = \App\Models\Users\UserAddress::where('usa_user_id', $user->id)
    ->where('usa_id', '=', input('address_id'))
    ->first();
if (!$address) {
    throw new RuntimeException('Bạn phải chọn địa chỉ nhận hàng', 400);
}
$userType = intval($user->use_type); // 1: tai khoan tieu dung. 2: tai khoan kinh doanh


$paymentType = input('payment_type');
if (!in_array($paymentType, array_keys(Order::paymentTypes()))) {
    throw new RuntimeException("Hình thức thanh toán không đúng", 400);
}
$from_admin = input('admin');
if ($from_admin) {
    $user_carts = $user->cartAdmin ?? false;
    app('auth')->u_id = $user->use_id;
    app('auth')->use_is_seller = $user->use_is_seller;
    app('auth')->use_family = $user->use_family;
} else {
    $user_carts = $user->cart ?? false;
}
if (empty($user_carts)) {
    throw new RuntimeException("Giỏ hàng của bạn không có sản phẩm nào", 400);
}

$total_money = 0;
$total_money_percent = 0;
$total_money_percent_wholesale = 0;
$total_commission = 0;
$total_direct_commission = 0;
$total_product = 0;
$data_order_products = [];
$discount_type = input('discount_type') ?? 1;
$is_wholesale = 0;
$total_point = 0;
$data_commission_products = [];
foreach ($user_carts as $user_cart) {
    if (!($user_cart->product ?? false)) {
        continue;
    }
    // nếu có 1 sản phẩm nằm trong sản phẩm hết hàng thì thông báo lỗi luôn
    if(intval($user_cart->product->pro_active_inventory) == 2){
        throw new RuntimeException("Tồn tại sản phẩm hết hàng!", 400);
    }
    $total_money_percent_wholesale += $user_cart->product->price * $user_cart->quantity;
}
$configData = Setting::where('swe_key', 'LIKE', 'tong_gia_tri_don_hang_huong_uu_dai_%')->select_all();
$arrconfigData = array();
foreach($configData as $items){
    $arrconfigData[] = $items->swe_value_vn;
}
// tính giá theo lever
$leverPrice = 0;
if($total_money_percent_wholesale > 0 && $total_money_percent_wholesale >= $arrconfigData[count($arrconfigData)-1]){
    $leverPrice = count($arrconfigData);
    $is_wholesale = 1;
}else{
    for($i=0;$i<count($arrconfigData);$i++){
        if($total_money_percent_wholesale > 0 && $total_money_percent_wholesale >= $arrconfigData[$i] && $total_money_percent_wholesale < $arrconfigData[$i+1]){
            $leverPrice = $i+1;
            $is_wholesale = 1;
        }
    }
}
foreach ($user_carts as $user_cart) {
    if (!($user_cart->product ?? false) || $is_wholesale) {
        continue;
    }
    $user_cart->product->buy_quantity = $user_cart->quantity;
    $product = transformer_item($user_cart->product, new \App\Transformers\ProductTransformer(), ['pricePolicies']);
    
    if ($product['is_wholesale']) {
        $is_wholesale = 1;
    }
}
$totalCommissionNew = 0;
foreach ($user_carts as $user_cart) {
    if (!($user_cart->product ?? false)) {
        continue;
    }
    $user_cart->product->buy_quantity = $user_cart->quantity;
    $user_cart->product->is_wholesale = $is_wholesale;
    $user_cart->product->leverPrice = $leverPrice;
    $product = transformer_item($user_cart->product, new \App\Transformers\ProductTransformer(), ['pricePolicies']);
    $product = collect_recursive($product);

    $total_product += $user_cart->quantity;
	if ($discount_type == 2) {
		$price = $product->price;
	}else{
		$price = $product->discount_price ? $product->discount_price : $product->price;
	}
    //$price = $product->discount_price ? $product->discount_price : $product->price;

    $total_money += $price * $user_cart->quantity;
    $total_money_percent += $product->price * $user_cart->quantity;

    $direct_commission = 0;
    $min_price_policy = 0;
    //Tính hoa hồng khi khách mua buôn
    if ($is_wholesale) {
        $price = $productTmp['db_discount_price'] ?: $productTmp['db_price'];
        $direct_commission = ($product->price - $product->discount_price) * $user_cart->quantity;
        $total_direct_commission += $direct_commission;
        $min_price_policy = (int)$product->min_price_policy->price * $user_cart->quantity;
    }

    $commission = $product->paid_commission * $user_cart->quantity;

    $commission_sale_price = $commission - $direct_commission + $min_price_policy;
    $commission_sale_price = $commission_sale_price > 0 ? $commission_sale_price : 0;
    //
    if ($is_wholesale) {
        $commission_sale_price = ($product->discount_price + $product->paid_commission - $product->min_price_policy->real_price) * $user_cart->quantity;
    } else {
        $commission_sale_price = $product->paid_commission * $user_cart->quantity;
    }
    $data_commission_products[$product->id] = $commission_sale_price;


    $total_commission += $commission_sale_price;

    if($userType == 1){ // Tính hoa hông moi
        // Hoa hồng mới = (Chiết khấu mức 2 - Chiết khấu mức 1) * Số lượng
        $pricePoliciesArr = $product->pricePolicies->toArray();
        $totalCommissionNew += ($pricePoliciesArr[1] - $pricePoliciesArr[0]) * $user_cart->quantity;
    }
    $total_point += $product->point * $user_cart->quantity;

    $atrrProd = [
        'orp_product_id' => $product->id,
        'orp_quantity' => $user_cart->quantity,
        'orp_price' => $product->price,
        'orp_sale_price' => $product->discount_price ? $product->discount_price : $product->price,
        'orp_commit_current' => $commission_sale_price,
        'orp_commission_buy' => $product->commission,
    ];

    $data_order_products[] = $atrrProd;
}
$total_money_lever  = 0;
$arr_money_lever  = [];
if(!$v_off_gia_sp_lever && $leverPrice > 0){
    foreach ($user_carts as $user_cart) {
        $pro_id = intval($user_cart->product->pro_id);
        $price = intval($user_cart->product->price);
        if($pro_id <= 0){
            break;
        }
        $querryString = '
            SELECT a.ppp_price
            FROM product_price_policies a 
                WHERE a.ppp_product_id = '.$pro_id.'
                ORDER BY ppp_price ASC LIMIT '.$leverPrice;   
        $db_price_policies = new db_query($querryString);
        $row_querry = $db_price_policies->fetch();
        $price_discount = intval($row_querry[count($row_querry)-1]['ppp_price']);
        if(!$price_discount){
            break;
        }
        $total_money_lever += ($price-$price_discount) * $user_cart->quantity;
        $arr_money_lever[$pro_id] = $price-$price_discount;
    }
}
// ưu tiên giá nhỏ hơn.
$lever_policies = ($total_money > $total_money_lever) ? $leverPrice : 0;
if($total_money_lever > 0 && $total_money > $total_money_lever){
    foreach($data_order_products as $key=>$items){
        if(isset($arr_money_lever[$items['orp_product_id']]) && intval($arr_money_lever[$items['orp_product_id']]) > 0){
            $data_order_products[$key]['orp_sale_price'] = intval($arr_money_lever[$items['orp_product_id']]);
        }
    }
    $total_money = $total_money_lever;
}
/*if ($discount_type == 2) {
    //$total_money += $total_direct_commission;
}*/

if (empty($data_order_products)) {
    throw new RuntimeException("Giỏ hàng của bạn không có sản phẩm nào");
}

//Tính tiền ship

if ($is_wholesale) {
    $ship_fee = _setting('shipping_fee_wholesale_over_thresshold', 0);
} else {
    if ($total_money <= _setting('shipping_fee_thresshold', 100000)) {
        $ship_fee = _setting('shipping_fee_under_thresshold', 35000);
    } else {
        $ship_fee = _setting('shipping_fee_retail_over_thresshold', 25000);
    }
}

// if ($total_money <= _setting('shipping_fee_thresshold', 100000)) {
//     $ship_fee = _setting('shipping_fee_under_thresshold', 35000);
// } else {
//     if ($is_wholesale) {
//         $ship_fee = _setting('shipping_fee_wholesale_over_thresshold', 0);
//     } else {
//         $ship_fee = _setting('shipping_fee_retail_over_thresshold', 25000);
//     }
// }

// $total_money += $ship_fee;

if ($paymentType == Order::PAYMENT_TYPE_WALLET) {
    if (!($user->wallet ?? false) || (int)$user->wallet->charge < $total_money) {

        \AppView\Helpers\Notification::to($user->id, 'Tài khoản không đủ tiền', 'Tài khoản của bạn không đủ để thanh toán đơn hàng. Hãy nạp thêm tiền vào tài khoản.');

        throw new RuntimeException('Ví tiêu dùng của bạn không đủ để thanh toán đơn hàng này', 400);
    }
}

if ($paymentType == Order::PAYMENT_TYPE_ONLINE) {

    if (!input('bank_id')) {
        throw new RuntimeException("Bạn phải chọn ngân hàng muốn thanh toán", 400);
    }
    $payment_bank_method = input('bank_id');

    if ($total_money < 10000) {
        throw new RuntimeException('Đơn hàng của bạn có giá trị nhỏ hơn 10.000đ. Vui lòng thanh toán khi nhận hàng hoặc thanh toán bằng Ví tiêu dùng', 400);
    }
}
/**
 * Create order
 */
//Thêm đơn hàng
$order_id = Order::insert([
    'ord_status_code' => Order::NEW,
    'ord_amount' => $total_money,
    'ord_user_id' => $user->id,
    'ord_ship_name' => $address->name,
    'ord_ship_address' => $address->address,
    'ord_ship_phone' => $address->phone,
    'ord_ship_email' => input('email'),
    'ord_note' => input('note') ?? '',
    'ord_payment_type' => input('payment_type') ?? 'COD',
    'ord_commission_type' => (int)($discount_type == 2 ? 2 : 1),
    //test
    'ord_code' => "Default",
    'ord_active' => ($paymentType == Order::PAYMENT_TYPE_ONLINE) ? 0 : 1,
    //    'ord_active' => 1,
    'ord_province_id' => (int)$address->province_id,
    'ord_district_id' => (int)$address->district_id,
    'ord_ward_id' => (int)$address->ward_id,
    'ord_address_id' => (int)$address->id,
    'ord_auto_shipping_fee' => (int)$ship_fee,
    'ord_lever_policies' => (int)$lever_policies
]);
//die;
if ($order_id) {
    /**
     * Create order product
     */
    foreach ($data_order_products as $dataOrderProduct) {
        $dataOrderProduct['orp_ord_id'] = $order_id;
        if ($discount_type == 2) {
            $dataOrderProduct['orp_sale_price'] = $dataOrderProduct['orp_price'];
        }
        $orderProd = \App\Models\OrderProduct::insert($dataOrderProduct);
    }

    /**
     * Lưu commission
     */

    //Get commission events
    $event_direct_commission_ratio = 0;
    $event_parent_commission_ratio = 0;
    $event = \App\Models\Event::where('evt_active', '>', 0)
        ->where('evt_start_time', '<', time())
        ->where('evt_end_time', '>', time())
        ->first();
    if ($event) {
        $event_direct_commission_ratio = $event->direct_commission_ratio;
        $event_parent_commission_ratio = $event->parent_commission_ratio;
    }
    //Commission trực tiếp
    $total_direct_commission = (int)($total_direct_commission / 100) * 100;

    $add_total_direct_commission = $total_direct_commission;
    $direct_vat = 0;
    if ($discount_type == 2) {
        $direct_vat = $total_direct_commission / 10;
        $direct_vat = (int)($direct_vat / 100) * 100;
        $add_total_direct_commission = $total_direct_commission - $direct_vat;
    }

    \App\Models\OrderCommission::insert([
        'orc_order_id' => $order_id,
        'orc_user_id' => $user->id,
        'orc_status_code' => (($discount_type == 2) ? \App\Models\OrderCommission::STATUS_NEW : \App\Models\OrderCommission::STATUS_SUCCESS),
        'orc_amount' => $add_total_direct_commission, //Làm tròn đến 100đ
        'orc_is_owner' => 1,
        'orc_is_direct' => 1,
        'orc_type' => $discount_type == 2 ? 0 : 1,
        'orc_vat' => $direct_vat,
        'orc_point' => 0
    ]);

    //Event direct commission
    if ($event_direct_commission_ratio > 1) {
        $event_direct_commission = ($event_direct_commission_ratio * $total_direct_commission) - $total_direct_commission;
        \App\Models\OrderCommission::insert([
            'orc_order_id' => $order_id,
            'orc_user_id' => $user->id,
            'orc_status_code' => \App\Models\OrderCommission::STATUS_SUCCESS,
            'orc_amount' => $event_direct_commission,
            'orc_is_owner' => 1,
            'orc_is_direct' => 1,
            'orc_type' => 1,
            'orc_vat' => 0,
            'orc_point' => 0,
            'orc_event_id' => (int)$event->id
        ]);
    }

    $commission_ratio = (int)setting('commission_ratio', 2);
    $commission_ratio = 1 / $commission_ratio;

    //Commission hệ thống
    $parent = $user;
    $parent_point = 0;
    $data_commission_products_total = [];
    $inserTotalcomissionNew = true;
    while ($parent && $total_commission > 0) {
        
        // insert comission moi
        if ($totalCommissionNew > 0 && $inserTotalcomissionNew && $parent->id != $user->id) {
            //Lưu user commssion
            \App\Models\OrderCommission::insert([
                'orc_order_id' => $order_id,
                'orc_user_id' => $parent->id,
                'orc_status_code' => \App\Models\OrderCommission::STATUS_NEW,
                'orc_amount' => 0,
                'orc_commission_backup' => 0,
                'orc_vat' => 0,
                'orc_is_owner' => 0,
                'orc_point' => 0,
                'orc_commission_buy' => 0,
                'orc_commission_new' => $totalCommissionNew,
            ]);
            $inserTotalcomissionNew =false;
        }
        //User Premium
        if ($parent->premium) {
            $user_commission_percent = $parent->premium_commission;
        } else {
            $user_commission_percent = setting('user_level_' . $parent->level . '_commission');
        }

        if ($user_commission_percent > 100) {
            $user_commission_percent = 100;
        }
        if ($user_commission_percent <= 0) {
            $user_commission_percent = 0;
        }

        if (!$user_commission_percent || $user_commission_percent > 100) {
            //Lưu user commssion
            \App\Models\OrderCommission::insert([
                'orc_order_id' => $order_id,
                'orc_user_id' => $parent->id,
                'orc_status_code' => \App\Models\OrderCommission::STATUS_NEW,
                'orc_amount' => 0,
                'orc_commission_backup' => $add_user_commission_backup,
                'orc_vat' => 0,
                'orc_is_owner' => ($parent->id == $user->id) ? 1 : 0,
                'orc_point' => 0,
                'orc_commission_buy' => $user_commission_percent,
            ]);
            $parent = $parent->parent;
            continue;
        }

        $user_commission = intval($user_commission_percent / 100 * $total_commission / 100) * 100;
        if ($user_commission >= 100) {
            

            $add_user_vat = $user_commission / 10;
            $add_user_vat = (int)($add_user_vat / 100) * 100;
            $add_user_commission = $user_commission - $add_user_vat;
            $add_user_commission_backup = $add_user_commission;
            # 13/9/2022 Giữ nguyên cơ chế chia quỹ tích luỹ nhưng chỉ chia cho người bán hàng (Người lên đơn), không chia cho người tuyến trên
            if ($parent->id != $user->id) {
                $add_user_commission = 0;
            }
            # 30/12/2022 Bổ sung không chia cho chính người lên đơn
            $add_user_commission = 0;
            // tính toán tổng chiếu khấu theo sản phấm
            // CT: commission_products * % user - VAT
            if(check_array($data_commission_products)){
                foreach($data_commission_products as $key=>$iems){
                    $commission_production = intval($iems);
                    $user_commission_production = intval($user_commission_percent / 100 * $commission_production / 100) * 100;
                    $add_user_commission_vat = $user_commission_production / 10;
                    $add_user_commission_vat = (int)($add_user_commission_vat / 100) * 100;
                    // Tính commit produc theo % user
                    $data_commission_products_total[$key] = intval($data_commission_products_total[$key]) + ($user_commission_production - $add_user_commission_vat);
                    $data_commission_products[$key] = $commission_production-$user_commission_production;
                }
            }
            //Lưu user commssion
            \App\Models\OrderCommission::insert([
                'orc_order_id' => $order_id,
                'orc_user_id' => $parent->id,
                'orc_status_code' => \App\Models\OrderCommission::STATUS_NEW,
                'orc_amount' => $add_user_commission,
                'orc_commission_backup' => $add_user_commission_backup,
                'orc_vat' => $add_user_vat,
                'orc_is_owner' => ($parent->id == $user->id) ? 1 : 0,
                'orc_point' => ($parent->id == $user->id) ? $total_point : $parent_point,
                'orc_commission_buy' => $user_commission_percent,
            ]);

            if ($parent->id == $user->id) {
                if ($event_direct_commission_ratio > 1) {
                    $user_commission = ($event_direct_commission_ratio * $user_commission) - $user_commission;
                    # 30/12/2022 Bổ sung không chia cho chính người lên đơn
                    $user_commission = 0;
                    \App\Models\OrderCommission::insert([
                        'orc_order_id' => $order_id,
                        'orc_user_id' => $user->id,
                        'orc_status_code' => \App\Models\OrderCommission::STATUS_SUCCESS,
                        'orc_amount' => $user_commission,
                        'orc_is_owner' => 1,
                        'orc_type' => 1,
                        'orc_point' => ($total_point * $event_direct_commission_ratio) - $total_point,
                        'orc_event_id' => (int)$event->id
                    ]);
                }
                $parent_point = $total_point;
            } else {
                if ($event_parent_commission_ratio > 1) {
                    $event_parent_commission = ($event_parent_commission_ratio * $user_commission) - $user_commission;
                    \App\Models\OrderCommission::insert([
                        'orc_order_id' => $order_id,
                        'orc_user_id' => $parent->id,
                        'orc_status_code' => \App\Models\OrderCommission::STATUS_SUCCESS,
                        'orc_amount' => $event_parent_commission,
                        'orc_is_owner' => 0,
                        'orc_type' => 1,
                        'orc_point' => ($event_parent_commission_ratio * $parent_point) - $parent_point,
                        'orc_event_id' => (int)$event->id
                    ]);
                }
                $parent_point = 0;
            }


            $total_commission -= $user_commission;
        }
        $parent = $parent->parent;
    }
    // Cập nhật lại giá sản phẩm
    if(check_array($data_commission_products_total)){
        foreach($data_commission_products_total as $key=>$iems){
            \App\Models\OrderProduct::update([
                'orp_commission_product' => intval($iems),
            ], 'orp_ord_id = ' . $order_id . ' AND orp_product_id = ' . intval($key));
        }
    }

    \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\OrderProductCommissionDetailWorker::$name, \App\Workers\OrderProductCommissionDetailWorker::class, [
        'order_id' => $order_id
    ]);

    /**
     * Order Code
     */

    $total_order_today = Order::withTrash()->where('DATE(ord_created_at) = \'' . date('Y-m-d') . '\'')->count();
    $total_order_today = $total_order_today + 1;
    $length = strlen($total_order_today);
    $zero_length = 5 - $length;
    $order_code = 'HD' . date('ymd');
    for ($i = 1; $i <= $zero_length; $i++) {
        $order_code = $order_code . '0';
    }

    $order_code = $order_code . $total_order_today;


    \App\Models\Order::update([
        'ord_amount' => $total_money,
        'ord_code' => $order_code,
    ], 'ord_id = ' . $order_id);

    //Log order
    \App\Models\OrderLog::insert([
        'orl_ord_id' => $order_id,
        'orl_old_status_code' => \App\Models\Order::NEW,
        'orl_new_status_code' => \App\Models\Order::NEW,
        'orl_old_payment_status' => Order::PAYMENT_STATUS_NEW,
        'orl_new_payment_status' => Order::PAYMENT_STATUS_NEW,
        'orl_note' => 'Đơn hàng mới tạo',
        'orl_updated_by' => (int)$input['admin_id']
    ]);

    //Xóa User Cart
    $test = getValue('test', 'int', 'GET', 0);

    //Xử lý thanh toán
    switch ($paymentType) {
        case Order::PAYMENT_TYPE_WALLET:
            //Thanh toán luôn
            $order = Order::findByID($order_id);
            //Ghi Logs order
            $orderLogId = OrderLog::insert([
                'orl_ord_id' => $order->id,
                'orl_old_status_code' => $order->status_code,
                'orl_new_status_code' => $order->status_code,
                'orl_old_payment_status' => $order->payment_status,
                'orl_new_payment_status' => Order::PAYMENT_STATUS_SUCCESS,
                'orl_note' => 'Người dùng thanh toán bằng ví tiêu dùng',
                'orl_updated_by' => (int)$input['admin_id']
            ]);

            //thanh đổi trạng thái payment
            Order::update(['ord_payment_status' => Order::PAYMENT_STATUS_SUCCESS, 'ord_payment_successed_at' => date('Y-m-d H:i:s')], 'ord_id = ' . $order->id);

            //Ghi Log Money
            sub_money($user->id, $order->amount, UserMoneyLog::TYPE_MONEY_ADD, 'Thanh toán đơn hàng ' . $order->code, $order_id, UserMoneyLog::SOURCE_TYPE_ORDER);

            \AppView\Helpers\Notification::to($user->id, 'Thanh toán đơn hàng thành công', 'Bạn vừa thanh toán thành công đơn hàng ' . $order->code . '. Chúng tôi sẽ xử lý đơn hàng sớm nhất');

            if (!$test) {
                if ($from_admin) {
                    \App\Models\Users\UserCartAdmin::where('usc_user_id', $user->id)->delete();
                } else {
                    UserCart::where('usc_user_id', $user->id)->delete();
                }
            }

            break;
        case Order::PAYMENT_TYPE_ONLINE:

            //            throw new Exception('Hình thức thanh toán tạm thời chưa hỗ trợ. Bạn hãy sử dụng ví hoặc COD nhé.', 400);

            $order = Order::findByID($order_id);

            //Tạo Log request
            $request_id = \App\Models\Users\UserMoneyAddRequest::insert([
                'umar_user_id' => $user->id,
                'umar_amount' => $order->amount,
                'umar_status' => \App\Models\Users\UserMoneyAddRequest::STATUS_NEW,
                'umar_type' => \App\Models\Users\UserMoneyAddRequest::TYPE_PAYMENT_ORDER,
                'umar_order_id' => $order->id,
                'umar_note' => 'Thanh toán hóa đơn ' . $order->code,
            ]);

            $client = new GuzzleHttp\Client(['timeout' => 20.0]);

            $payload['mrc_order_id'] = 'basaco_payment_' . $request_id;
            $payload['total_amount'] = (int)$order->amount;
            $payload['description'] = "Thanh toán đơn hàng " . $order_code;
            $payload['url_success'] = url('payment-callback') . '?success=1';
            $payload['url_detail'] = url('payment-callback') . '?cancel=1';
            $payload['lang'] = "vi";
            $payload['items'] = json_encode([]);
            $payload['bpm_id'] = $input['bank_id'] ?? 0;
            $payload['accept_bank'] = 1;
            $payload['accept_cc'] = 1;
            $payload['accept_qrpay'] = 1;
            $payload['webhooks'] = url('payment-callback-bpn');
            $payload['customer_email'] = strtolower((isset($input['email']) && filter_var($input['email'], FILTER_VALIDATE_EMAIL)) ? $input['email'] : 'default@dododo24h.com.vn');
            $payload['customer_phone'] = $input['phone'] ?? '';
            $payload['customer_name'] = $input['name'] ?? '';
            $payload['customer_address'] = $input['address'] ?? '';
            $options['form_params'] = $payload;

            $options['query']['jwt'] = BaoKimAPI::getToken($payload);

            $response = $client->request("POST", config('baokim.api_domain') . "/payment/api/v4/order/send", $options);
            $result = json_decode($response->getBody()->getContents(), true);
            if ($result['code'] > 0) {
                foreach ($result['message'] as $error) {
                    if (is_array($error)) {
                        foreach ($error as $item) {
                            throw new RuntimeException($item, 400);
                        }
                    } else {
                        throw new RuntimeException($error, 400);
                    }
                }
            } else {
                $vars['redirect_url'] = $result['data']['redirect_url'] ?? '';
                $vars['redirect_url'] = (false != strpos($vars['redirect_url'], 'https')) ? $vars['redirect_url'] : (config('baokim.website_domain') . $vars['redirect_url']);
            }

            //=================Return================
            return [
                'vars' => $vars
            ];

            break;
        default: //COD
            if (!$test) {
                if ($from_admin) {
                    \App\Models\Users\UserCartAdmin::where('usc_user_id', $user->id)->delete();
                } else {
                    UserCart::where('usc_user_id', $user->id)->delete();
                }
            }
            \AppView\Helpers\Notification::to($user->id, 'Đặt hàng thành công', 'Chúc mừng bạn đã đặt hàng thành công. Chúng tôi sẽ xử lý đơn hàng sớm nhất.');
            break;
    }

    $current_order = \App\Models\Order::findByID($order_id);
    $vars = transformer_item($current_order, new \App\Transformers\OrderTransformer());

    Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
        'user_id' => $current_order->user->id,
        'order_id' => $current_order->id
    ]);
}

return [
    'vars' => $vars
];
