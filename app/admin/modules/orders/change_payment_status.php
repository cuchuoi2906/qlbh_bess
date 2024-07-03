<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

use App\Models\OrderLog;

disable_debug_bar();
//check quyền them sua xoa
checkAddEdit("edit");

$orderId = getValue('order_id', 'int', 'POST', 0);
$status = getValue('status', 'str', 'POST', 0);
$note = getValue('note', 'str', 'POST', '');

$order = \App\Models\Order::findByID($orderId);
if (!$order) {
    die('Đơn hàng không tồn tại');
}
if ($order->status_code == \App\Models\Order::SUCCESS) {
    die('Không thể chuyển trạng thái thanh toán của đơn hàng này.');
}

if ($status == $order->payment_status) {
    die('Không thể chuyển cùng trạng thái đơn hàng');
}


try {

    //Log Order
    //Ghi Logs order
    $orderLogId = OrderLog::insert([
        'orl_ord_id' => $order->id,
        'orl_old_status_code' => $order->status_code,
        'orl_new_status_code' => $order->status_code,
        'orl_old_payment_status' => $order->payment_status,
        'orl_new_payment_status' => $status,
        'orl_updated_by' => $admin_id,
        'orl_updated_at' => date('Y-m-d H:i:s'),
        'orl_note' => $note
    ]);

    admin_log($admin_id, ADMIN_LOG_ACTION_ACTIVE, $order->id, 'Đã thay đổi trạng thái thanh toán của đơn hàng (' . $order->id . ') từ ' . $order->payment_status . ' thành ' . $status);

    $order->payment_status = $status;
    if ($status == \App\Models\Order::PAYMENT_STATUS_SUCCESS) {
        $order->payment_successed_at = date('Y-m-d H:i:s');
    }
    $check = $order->update();

    \AppView\Helpers\Notification::to([$order->user_id], 'Thay đổi trạng thái thanh toán đơn hàng', 'Đơn hàng ' . $order->code . ' vừa chuyển sang trạng thái ' . \App\Models\Order::paymentStatus()[$status]);

} catch (Exception $e) {
    die($e->getMessage());
}