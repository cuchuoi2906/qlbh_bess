<?php

use App\Models\Order;
$order = \App\Models\Order::where('ord_id', input('order_id'))
    ->first();

if (!$order) {
    throw new RuntimeException('Không tồn tại đơn hàng này', 404);
}

\App\Models\Order::update([
    'ord_stock_check_status' => input('status')
], 'ord_id = ' . input('order_id'));

$vars['order_id'] = input('order_id');
return [
    'vars' => $vars
];
