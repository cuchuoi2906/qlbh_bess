<?php

use App\Models\Order;

require_once("../../bootstrap.php");
require_once 'inc_security.php';
disable_debug_bar();

$order_id = getValue('order_id', 'int', 'POST');
$user_id = getValue('user_id', 'int', 'POST');
$order = Order::findByID($order_id);
if (!$order) {
    throw new Exception('Đơn hàng không tồn tại', 404);
}

try {
    $result = repository('order/renew')->load([
        'id' => $order->id,
        'user_id' => $user_id,
        'admin_id' => $admin_id,
        'note' => 'Hủy để tạo lại đơn'
    ]);

    admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $order->id, 'Đã hủy đơn hàng ' . $order->code . ' để tạo lại đơn hàng mới');

    echo 1;
} catch (Exception $e) {
    echo $e->getMessage();
}
die;
