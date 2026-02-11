<?php
require_once 'inc_security.php';
use App\Models\Product;

$order_id = getValue('ord_id', 'int', 'GET');
$type = getValue('type', 'int', 'GET');
var_dump($order_id);

$order = \App\Models\Order::findByID($order_id);

if(!$order){
    return;
}
$v_arr_data = [];
$products = $order->products;
$ord_status_code = $order->ord_status_code;

$arrProduct = [];
$i = 0;
foreach ($order->products as $product){
    $arrProduct[$i]['stt'] = $i+1;
    $arrProduct[$i]['name'] = $product->info->name;
    $arrProduct[$i]['quantity'] = $product->quantity;
    $arrProduct[$i]['sale_price'] = $product->sale_price;
    $arrProduct[$i]['price_hapu'] = $product->price_hapu;
    $arrProduct[$i]['pharmacy_hapu'] = $product->pharmacy_hapu;
    $arrProduct[$i]['note_hapu'] = $product->note_hapu;
    $i++;
}
usort($arrProduct, function ($a, $b) {
    return strcmp($a['name'], $b['name']); // So sánh theo tên (tăng dần)
});
    
$users = $order->user;
$ord_vat = $order->ord_vat;
$v_arr_data[0][] = 'STT';
$v_arr_data[0][] = 'Tên sản phẩm';
$v_arr_data[0][] = 'Quầy';
$v_arr_data[0][] = 'Số lượng';
$v_arr_data[0][] = 'Giá Bán';
if($ord_status_code == \App\Models\Order::NEW && $type == 1){
    $v_arr_data[0][] = 'Giá Nhập';
    $v_arr_data[0][] = 'Chênh lệch';
    $v_arr_data[0][] = 'Ghi chú';
}
$v_arr_data[0][] = 'Tổng Bán';
if($ord_status_code != \App\Models\Order::NEW){
    $v_arr_data[0][] = 'Giá Nhập';
    $v_arr_data[0][] = 'Tổng Nhập';
    $v_arr_data[0][] = 'Chênh lệch';
}
$i= 1;
$total_price_origin = 0;
$totalAmountAll_hapu = 0;
foreach ($arrProduct as $product) {
    $v_arr_data[$i][] = $i;
    $v_arr_data[$i][] = $product['name'];
    $v_arr_data[$i][] = $product['pharmacy_hapu'];
    $v_arr_data[$i][] = $product['quantity'];
    $v_arr_data[$i][] = $product['sale_price'];
    if($ord_status_code == \App\Models\Order::NEW && $type == 1){
        $v_arr_data[$i][] = $product['price_hapu'];
        $v_arr_data[$i][] = $product['sale_price'] - $product['price_hapu'];
        $v_arr_data[$i][] = $product['note_hapu'];
    }
    $v_arr_data[$i][] = $product['sale_price'] * $product['quantity'];
    if($ord_status_code != \App\Models\Order::NEW){
        $v_arr_data[$i][] = $product['price_hapu'];
        $v_arr_data[$i][] = $product['price_hapu'] * $product['quantity'];
        $v_arr_data[$i][] = $product['sale_price'] - $product['price_hapu'];
    }
    $i++;
    $total_price_origin += $product['quantity'] * $product['sale_price'];
    $totalAmountAll_hapu += $product['quantity'] * $product['price_hapu'];
}
/*$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = 'VAT';
$v_arr_data[$i][] = $total_price_origin * 0.1;*/

$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
if($ord_status_code == \App\Models\Order::NEW && $type == 1){
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
}
$v_arr_data[$i][] = 'Tổng tiền';
$v_arr_data[$i][] = $total_price_origin;
$v_arr_data[$i][] = '';
if($ord_status_code != \App\Models\Order::NEW){
    $v_arr_data[$i][] = $totalAmountAll_hapu;
    $v_arr_data[$i][] = $total_price_origin - $totalAmountAll_hapu;
}
$i = $i +1;
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
if($ord_status_code == \App\Models\Order::NEW && $type == 1){
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
}
$v_arr_data[$i][] = 'Tổng ship';
$v_arr_data[$i][] = $order->ord_shipping_fee_car;
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$v_arr_data[$i][] = '';
$i = $i +1;
if($ord_status_code != \App\Models\Order::NEW){
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = 'Lỗ/Lãi';
    $v_arr_data[$i][] = $total_price_origin - ($totalAmountAll_hapu+$order->ord_shipping_fee_car);
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
    $v_arr_data[$i][] = '';
}
$nameFile = $order->code.'__'.$users->use_name;
export_custom($v_arr_data,$nameFile);
die;
