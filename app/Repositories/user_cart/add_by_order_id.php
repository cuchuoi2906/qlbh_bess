<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 13:54
 */

use App\Models\Users\UserCart;
use App\Models\Order;

$affected = 0;
$model = UserCart::class;

//Check sản phẩm tồn tại
$order = \App\Models\Order::findByID(input('order_id'));
if ($order) {
    $products = $order->products;
    $arr_user_cart = [];
    foreach($products as $items){
        $arr_user_cart = [
            'usc_user_id' => (int)input('user_id'),
            'usc_product_id' => (int)$items['orp_product_id'],
            'usc_quantity' => (int)$items['orp_quantity']
        ];
        $model::insertUpdate($arr_user_cart,[
            'usc_quantity' => (int)$items['orp_quantity']
        ]);
    }
    $affected = 1;
}

return [
    'vars' => $affected
];