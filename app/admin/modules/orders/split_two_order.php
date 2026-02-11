<?php

require_once 'inc_security.php';
use App\Models\Product;
use App\Models\Order;

$order_id = getValue('id', 'int', 'POST');

date_default_timezone_set('Asia/Ho_Chi_Minh');
$total_order_today = Order::withTrash()->where('DATE(ord_created_at) = \'' . date('Y-m-d') . '\'')->count();
$total_order_today = $total_order_today + 1;
$length = strlen($total_order_today);
$zero_length = 5 - $length;

$order_code = 'HD' . date('ymd');
for ($i = 1; $i <= $zero_length; $i++) {
    $order_code = $order_code . '0';
}

$order_code = $order_code . $total_order_today;

$db_data = new db_query("Call be_clone_order_by_id(".$order_id.",'".$order_code."','".Order::CANCEL."',1)");
$row = $db_data->fetch();

$arr_err['KHONG_CO_GIA_NHAP_BANG_0'] = "Không có giá nhập nào = 0";
$arr_err['KHONG_CO_GIA_NHAP_LON_HON_0'] = "Không có giá nhập nào > 0";
$arr_err['SQL_ERROR'] = "Có lỗi Sql.";
if(isset($row[0]['RET_ERROR'])){
    die($arr_err[$row[0]['RET_ERROR']]);
}
if(intval($row[0]['order_id']) > 0){
    \App\Manager\Order\OrderManager::commissions(intval($row[0]['order_id']));
    
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $total_order_today = Order::withTrash()->where('DATE(ord_created_at) = \'' . date('Y-m-d') . '\'')->count();
    $total_order_today = $total_order_today + 1;
    $length = strlen($total_order_today);
    $zero_length = 5 - $length;

    $order_code = 'HD' . date('ymd');
    for ($i = 1; $i <= $zero_length; $i++) {
        $order_code = $order_code . '0';
    }

    $order_code = $order_code . $total_order_today;
    //echo "Call be_clone_order_by_id(".$order_id.",'".$order_code."','".Order::CANCEL."',0)";
    $db_data = new db_query("Call be_clone_order_by_id(".$order_id.",'".$order_code."','".Order::CANCEL."',0)");
    $row1 = $db_data->fetch();
    if(intval($row1[0]['order_id']) > 0){
        \App\Manager\Order\OrderManager::commissions(intval($row1[0]['order_id']));
    }
}
die("");