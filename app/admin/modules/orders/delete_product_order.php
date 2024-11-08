<?php
use App\Models\Order;

require_once 'inc_security.php';
checkAddEdit("add");

disable_debug_bar();

$orderId = getValue('orderId', 'int', 'GET');
$id = getValue('id', 'int', 'GET');
$user_id = getValue('user_id');

\App\Models\OrderProduct::where('orp_id', '=', $id)->delete();

//Tính lại hoa hồng
\App\Manager\Order\OrderManager::commissions($orderId);

$order = Order::findByID($orderId);

//Log
\App\Models\OrderLog::insert([
    'orl_ord_id' => $orderId,
    'orl_old_status_code' => $order->status_code,
    'orl_new_status_code' => $order->status_code,
    'orl_old_payment_status' => $order->payment_status,
    'orl_new_payment_status' => $order->payment_status,
    'orl_updated_by' => $admin_id ?? 0,
    'orl_note' => 'Xóa sản phẩm đơn hàng ở màn hình chi tiết'
]);
echo $orderId;