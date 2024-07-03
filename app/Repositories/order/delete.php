<?php

/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/22/20
 * Time: 11:18
 */

use App\Models\Order;
use App\Workers\TotalTeamPointDayWorker;
use VatGia\Queue\Facade\Queue;

$vars = false;

$user = \App\Models\Users\Users::findByID(input('user_id'));
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

$order = \App\Models\Order::where('ord_user_id', $user->id)
    ->where('ord_id', input('id'))
    ->first();

if (!$order) {
    throw new RuntimeException('Không tồn tại đơn hàng này', 404);
}

if ($order->status_code == Order::SUCCESS) {
    throw new RuntimeException('Đơn hàng đã thành công. Phiền quý khách liên hệ để được hỗ trợ', 400);
}

if ($order->status_code == Order::CANCEL) {
    throw new RuntimeException('Đơn hàng đã hủy. Phiền quý khách liên hệ để được hỗ trợ', 400);
}

if ($order->status_code != \App\Models\Order::NEW) {
    throw new RuntimeException('Đơn hàng này đang được xử lý. Phiền quý khách liên hệ để được hỗ trợ', 400);
}

if ($order->payment_type != \App\Models\Order::PAYMENT_TYPE_COD && $order->payment_type != \App\Models\Order::PAYMENT_TYPE_BANKING) {
    throw new RuntimeException('Quý khách không thể hủy đơn hàng này. Phiền quý khách liên hệ để được hỗ trợ', 400);
}

$vars = \App\Models\Order::where('ord_id', $order->id)->update([
    'ord_status_code' => \App\Models\Order::CANCEL
]);

Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
    'user_id' => $order->user->id,
    'order_id' => $order->id
]);

if ($vars) {
    \App\Models\OrderLog::insert([
        'orl_ord_id' => $order->id,
        'orl_old_status_code' => $order->status_code,
        'orl_new_status_code' => \App\Models\Order::CANCEL,
        'orl_old_payment_status' => $order->payment_status,
        'orl_new_payment_status' => $order->payment_status,
        'orl_updated_at' => date('Y-m-d H:i:s'),
        'orl_note' => input('note') ?? 'Người dùng hủy',
        'orl_updated_by' => (int)(input('admin_id') ?? -1)
    ]);
}


return [
    'vars' => [
        'success' => $vars
    ]
];
