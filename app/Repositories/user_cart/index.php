<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 14:11
 */
use App\Models\Setting;
$vars = [];

$model = new \App\Models\Users\UserCart;

if (input('admin')) {
    $model = new \App\Models\Users\UserCartAdmin;
}

$user = \App\Models\Users\Users::findByID(input('user_id'));

$items = $model->modelMustHave(['products'])
    ->where('usc_user_id', input('user_id'))
    ->all();
$is_wholesale = 0;
if ($items->count()) {

    $total_product = 0;
    $total_money = 0;
    $total_money_percent = 0;
    $total_money_percent_wholesale = 0;
    $total_money_origin = 0;
    $total_discount = 0;
    $total_commission = 0;
    $total_direct_commission = 0;
    $total_point = 0;
    $total_product_cart = 0;
    // lặp giỏ hảng tính mix giá
    foreach ($items as $key => $item) {
        if (!($item->product ?? false)) {
            $item->delete();
            unset($items[$key]);
            continue;
        }
        $item->product->buy_quantity = $item->quantity;
        $item->product->leverPrice = 0;
        $total_money_percent_wholesale += $item->product->price * $item->quantity;
    }
    $configData = Setting::where('swe_key', 'LIKE', 'tong_gia_tri_don_hang_huong_uu_dai_%')->select_all();
    $arrconfigData = array();
    foreach($configData as $item){
        $arrconfigData[] = $item->swe_value_vn;
    }
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
    foreach ($items as $key => $item) {

        if ($is_wholesale) {
            continue;
        }

        if ($item->quantity <= 0) {
            $item->delete();
            unset($items[$key]);
            continue;
        }
        if (!($item->product ?? false)) {
            $item->delete();
            unset($items[$key]);
            continue;
        }

        $item->product->buy_quantity = $item->quantity;
        $item->product->leverPrice = $leverPrice;
        $product_temp = $item->product;

        $product = transformer_item($product_temp, new \App\Transformers\ProductTransformer());
        
        if ($product['is_wholesale']) {
            $is_wholesale = 1;
        }

    }
    
    $v_discount_price_arr = [];
    $v_quantity_old = [];
    foreach ($items as $key => &$item) {

        $item->product->buy_quantity = $item->quantity;
        $item->product->is_wholesale = $is_wholesale;
        $item->product->leverPrice = $leverPrice;
        $product_temp = $item->product;

        $product = transformer_item($product_temp, new \App\Transformers\ProductTransformer());

        $price = $product['is_discount'] ? $product['discount_price'] : $product['price'];
        $price = $product['price_policy'] ? $product['price_policy'] : $price;
        $v_discount_price_arr[]= $price;
        $v_quantity_old[]= $item->quantity;
        $total_product += (int)$item->quantity;
        $total_money += $price * $item->quantity;
        $total_money_percent += $product['price'] * $item->quantity;
        $total_money_origin += $product['price'] * $item->quantity;
        $total_discount += ($product['price'] * $item->quantity - $price * $item->quantity);

        //$price = $product['is_discount'] ? $product['discount_price'] : $product['price'];
        $productTmp = collect_recursive($product);

        $direct_commission = 0;
        $min_price_policy = 0;
        //Tính hoa hồng
        if ($productTmp->discount_price) {

            // $price = $productTmp['db_discount_price'] ?: $productTmp['db_price'];

            $direct_commission = ($productTmp->price - $productTmp->discount_price) * $item->quantity;
            $total_direct_commission += $direct_commission;
            $min_price_policy = (int)$productTmp->min_price_policy->price * $item->quantity;
        }

        $commission = $productTmp->paid_commission * $item->quantity;

        // $commission_sale_price = $commission - $direct_commission + $min_price_policy;
        // $commission_sale_price = $commission_sale_price > 0 ? $commission_sale_price : 0;

        if ($is_wholesale) {
            $commission_sale_price = ($productTmp->discount_price + $productTmp->paid_commission - $productTmp->min_price_policy->real_price) * $item->quantity;
        } else {
            $commission_sale_price = $productTmp->paid_commission * $item->quantity;
        }

        $total_commission += $commission_sale_price;
        $total_point += $productTmp->point * $item->quantity;

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

    if ($user->premium) {
        $user_commission_percent = $user->premium_commission;
    } else {
        $user_commission_percent = setting('user_level_' . $user->level . '_commission');
    }

    if ($user_commission_percent > 100) {
        $user_commission_percent = 100;
    }
    if ($user_commission_percent <= 0) {
        $user_commission_percent = 0;
    }

    $user_commission_percent = $user_commission_percent / 100;

    $commissionReturn = ($total_commission * $user_commission_percent + $total_direct_commission);
    $commissionWithoutDirect = ($commissionReturn - $total_direct_commission);
    $commissionReturn = $commissionReturn * 0.9;
    $commissionWithoutDirect = $commissionWithoutDirect * 0.9;
    $commissionReturn = (int)($commissionReturn / 100) * 100;
    $commissionWithoutDirect = (int)($commissionWithoutDirect / 100) * 100;

    $vars = transformer_collection($items, new \App\Transformers\UserCartTransformer(), ['product.avatar','product.pricePolicies'], [
        'total_product' => $total_product ?? 0,
        'total_money' => $total_money ?? 0,
        'total_money_origin' => $total_money_origin ?? 0,
        'total_discount' => $total_discount ?? 0,
        'total_direct_commission' => $total_direct_commission * 0.9,
        'total_commission' => 0, // $commissionReturn
        'total_commission_display' => 0, // number_format($commissionReturn)
        'total_commission_without_direct' => 0,// $commissionWithoutDirect
        'total_commission_without_direct_display' => 0, // number_format($commissionWithoutDirect)
        'shipping_fee' => (int)$ship_fee,
        'shipping_fee_display' => number_format($ship_fee),
        'total_point' => $total_point,
        'is_whole_sale' => $is_wholesale,
        'total_product_cart'=>$items->count()
    ]);
    // Xu ly lay discount_price de hiên thi lai tren app
    for($i=0;$i<50;$i++){
        if(!isset($vars[$i]['product']) || !check_array($vars[$i]['product'])){
            break;
        }
        $vars[$i]['product']['discount_price'] = (!intval($vars[$i]['product']['is_discount']) && !intval($vars[$i]['product']['discount_price'])) ? $vars[$i]['product']['price'] : $vars[$i]['product']['discount_price'];
    }
}
return [
    'vars' => $vars
];