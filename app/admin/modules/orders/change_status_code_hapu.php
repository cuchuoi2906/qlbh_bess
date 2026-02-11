<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderCommission;
use App\Workers\TotalTeamPointDayWorker;
use VatGia\Queue\Facade\Queue;

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
    die('Đơn hàng đã thành công. Không thể chuyển trạng thái');
}

if ($status == $order->status_code) {
    die('Không thể chuyển cùng trạng thái đơn hàng');
}

Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
    'user_id' => $order->user->id,
    'order_id' => $order->id
]);


$order->ord_status_process = $status;
$check = $order->update();
\AppView\Helpers\Notification::to([$order->user_id], 'Thay đổi trạng thái đơn hàng', 'Đơn hàng ' . $order->code . ' vừa chuyển sang trạng thái ' . $array_status_hapu[$status]);
