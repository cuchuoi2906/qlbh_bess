<?php
require_once 'inc_security.php';
use App\Models\Product;

$order_id = getValue('ord_id', 'int', 'GET');
var_dump($order_id);

$order = \App\Models\Order::findByID($order_id);
if(!$order){
    return;
}
$v_arr_data = [];
$products = $order->products;
$v_arr_data[0][] = 'STT';
$v_arr_data[0][] = 'Tên sản phẩm';
$v_arr_data[0][] = 'Số lượng';
$v_arr_data[0][] = 'Giá';
$v_arr_data[0][] = 'Tổng tiền';
$i= 1;
$total_price_origin = 0;
foreach ($products as $product) {
    $v_arr_data[$i][] = $i;
    $v_arr_data[$i][] = $product->info->name;
    $v_arr_data[$i][] = $product->orp_quantity;
    $v_arr_data[$i][] = $product->orp_sale_price;
    $v_arr_data[$i][] = $product->orp_sale_price * $product->orp_quantity;
    $i++;
    $total_price_origin += $product->orp_quantity * $product->orp_sale_price;
}
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = 'Tổng tiền';
$v_arr_data[$i][] = $order->amount;

$nameFile = $order->code;
export_custom($v_arr_data,$nameFile);
die;
