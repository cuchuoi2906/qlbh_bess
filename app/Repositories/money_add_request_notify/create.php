<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 1/15/20
 * Time: 06:01
 */

$vars = false;

$order_code = input('order_code') ?? '';
$order_id = 0;
if ($order_code) {
    $order = \App\Models\Order::where('ord_code', $order_code)->first();
    if (!$order) {
        throw new RuntimeException('Mã đơn hàng không đúng', 400);
    }
    $order_id = (int)$order->id;
}


$vars = \App\Models\MoneyAddRequestNotify::insert([
    'marn_user_id' => input('user_id'),
    'marn_account_name' => input('account_name') ?? '',
    'marn_account_number' => input('account_number') ?? '',
    'marn_bank_name' => input('bank_name') ?? '',
    'marn_trade_code' => input('trade_code') ?? '',
    'marn_money' => input('money') ?? 0,
    'marn_order_id' => (int)$order_id,
    'marn_image' => input('image'),
    'marn_type' => input('type') ?? 0
]);

return [
    'vars' => $vars
];