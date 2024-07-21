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
$userType = intval($user->use_type); // 1: tai khoan tieu dung. 2: tai khoan kinh doanh

$user_carts = $user->cart ?? false;


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


$totalCommissionNew = 0;
foreach ($user_carts as $user_cart) {
    if (!($user_cart->product ?? false)) {
        continue;
    }
    $user_cart->product->buy_quantity = $user_cart->quantity;
    $user_cart->product->is_wholesale = $is_wholesale;
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

if (empty($data_order_products)) {
    throw new RuntimeException("Giỏ hàng của bạn không có sản phẩm nào");
}
if ($total_money <= _setting('shipping_fee_thresshold', 100000)) {
    $ship_fee = _setting('shipping_fee_under_thresshold', 35000);
} else {
    $ship_fee = _setting('shipping_fee_retail_over_thresshold', 25000);
}

/**
 * Create order
 */
//Thêm đơn hàng
$order_id = Order::insert([
    'ord_status_code' => Order::NEW,
    'ord_amount' => $total_money,
    'ord_user_id' => $user->id,
    'ord_ship_name' => 'ver3',
    'ord_ship_address' => $user->use_address_register,
    'ord_ship_phone' => $user->login,
    'ord_ship_email' => input('email'),
    'ord_note' => input('note') ?? '',
    'ord_payment_type' => input('payment_type') ?? 'COD',
    'ord_commission_type' => (int)($discount_type == 2 ? 2 : 1),
    //test
    'ord_code' => "Default",
    'ord_active' => 1,
    //    'ord_active' => 1,
    //'ord_province_id' => (int)$address->province_id,
    //'ord_district_id' => (int)$address->district_id,
    //'ord_ward_id' => (int)$address->ward_id,
    //'ord_address_id' => (int)$address->id,
    'ord_auto_shipping_fee' => (int)$ship_fee,
    //'ord_lever_policies' => (int)$lever_policies
    'ord_shipping_code'=>'ver3',
    'ord_shipping_fee'=>'0',
    'ord_shipping_carrier'=>'ver3',
    'ord_lever_policies'=>0
]);

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
        'orl_updated_by' => $user->id
    ]);
    unset($_SESSION['cartTotalProduct']);
    
    // Xóa sản phẩm ở giỏ hàng
    UserCart::where('usc_user_id', $user->id)->delete();
    //throw new RuntimeException("Đặt hàng thành công quản trị viên sẽ liên hệ với bạn sớm nhất", 200);
    //\AppView\Helpers\Notification::to($user->id, 'Đặt hàng thành công', 'Chúc mừng bạn đã đặt hàng thành công. Chúng tôi sẽ xử lý đơn hàng sớm nhất.');
    $current_order = \App\Models\Order::findByID($order_id);
    $vars = transformer_item($current_order, new \App\Transformers\OrderTransformer());
}
return [
    'vars' => $vars
];