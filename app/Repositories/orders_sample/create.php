<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/29/18
 * Time: 20:16
 */

$vars = false;

//Thêm đơn hàng
$order_id = \App\Models\Order::insert([
    'ord_status_code' => \App\Models\Order::NEW,
    'ord_amount' => 0,
    'ord_user_id' => app('auth')->u_id,
    'ord_ship_name' => input('name'),
    'ord_ship_address' => input('address'),
    'ord_ship_phone' => input('phone'),
    'ord_ship_email' => input('email'),
    'ord_note' => input('note') ?? '',
    'ord_payment_type' => input('payment_type') ?? 'COD'
]);

if ($order_id) {
    //Info cart
    $total_money = 0;
    foreach (input('products') as $product) {
        $product_id = $product['id'];
        $quantity = $product['qty_order'];
        $product = \App\Models\Product::findByID($product_id);
        $product = transformer_item($product, new \App\Transformers\ProductTransformer());
        $price = $product['is_discount'] ? $product['discount_price'] : $product['price'];
        $total_money += $quantity * $price;

        \App\Models\OrderProduct::insert([
            'orp_ord_id' => (int)$order_id,
            'orp_product_id' => (int)$product['id'],
            'orp_quantity' => $quantity,
            'orp_price' => $product['price'],
            'orp_sale_price' => $product['discount_price']
        ]);
    }

    //Update tổng tiền
    $hashids = new \Hashids\Hashids(config('app.key'), 5, 'abcdefghiklmnopqrstuvwxyz');
    //Update SKU

    $length = strlen($order_id);
    $zero_length = 5 - $length;
    $order_code = 'HD';
    for ($i = 1; $i <= $zero_length; $i++) {
        $order_code = $order_code . '0';
    }
    $order_code = $order_code . $order_id;
//    $product = \App\Models\Product::findByID($pro_id);
//    $product->pro_sku = $pro_sku;
//    $product->update();


    \App\Models\Order::update([
        'ord_amount' => $total_money,
//        'ord_code' => $hashids->encode($order_id),
        'ord_code' => $order_code,
    ], 'ord_id = ' . $order_id);

    //Log order
    \App\Models\OrderLog::insert([
        'orl_ord_id' => $order_id,
        'orl_old_status_code' => '',
        'orl_new_status_code' => \App\Models\Order::NEW
    ]);

    $vars = \App\Models\Order::findByID($order_id)->toArray();
}


return [
    'vars' => $vars
];