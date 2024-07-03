<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

use App\Models\OrderLog;

disable_debug_bar();
//check quyền them sua xoa
checkAddEdit("edit");

$orderId = getValue('order_id', 'int', 'POST', 0);
$note = getValue('note', 'str', 'POST', '');
$shipping_fee = getValue('shipping_fee', 'int', 'POST', 0);

$order = \App\Models\Order::findByID($orderId);
if (!$order) {
    die('Đơn hàng không tồn tại');
}

//Log Order
//Ghi Logs order
$orderLogId = OrderLog::insert([
    'orl_ord_id' => $order->id,
    'orl_old_status_code' => $order->status_code,
    'orl_new_status_code' => $order->status_code,
    'orl_old_payment_status' => $order->payment_status,
    'orl_new_payment_status' => $order->payment_status,
    'orl_updated_by' => $admin_id,
    'orl_updated_at' => date('Y-m-d H:i:s'),
    'orl_note' => 'Thay đổi phí vận chuyển từ ' . $order->shipping_fee . ' thành ' . $shipping_fee . ($note ? (' Ghi chú: ' . $note) : '')
]);

admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $order->id, 'Đã thay đổi phí vận chuyển của đơn hàng (' . $order->id . ') từ ' . $order->shipping_fee . ' thành ' . $shipping_fee);


$order->shipping_fee = (int)$shipping_fee;
$check = $order->update();