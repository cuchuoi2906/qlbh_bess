<?php

require_once 'inc_security.php';
use App\Models\Product;
use App\Models\Order;

checkAddEdit("add");
$product_id = getValue('id', 'int', 'POST');
$ord_id = getValue('ord_id', 'int', 'POST');
$product = Product::findByID($product_id);
$order = Order::findByID($order_id);


$atrrProd = [
    'orp_ord_id' => $ord_id,
    'orp_product_id' => $product_id,
    'orp_quantity' => 1,
    'orp_price' => $product->price,
    'orp_sale_price' => $product->discount_price ? $product->discount_price : $product->price,
    'orp_commit_current' => 0,
    'orp_commission_buy' => $product->commission,
];
$orderProd = \App\Models\OrderProduct::insert($atrrProd);

//Tính lại hoa hồng
\App\Manager\Order\OrderManager::commissions($ord_id);

//Log
\App\Models\OrderLog::insert([
    'orl_ord_id' => $ord_id,
    'orl_old_status_code' => $order->status_code,
    'orl_new_status_code' => $order->status_code,
    'orl_old_payment_status' => $order->payment_status,
    'orl_new_payment_status' => $order->payment_status,
    'orl_updated_by' => $admin_id ?? 0,
    'orl_note' => 'Thêm mới sản phẩm trong BE '
]);
echo $id;