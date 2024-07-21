<?php
use App\Transformers\OrderTransformer;
use App\Models\Order;

$vars = [];

$order_id = input('id') ?? 0;

if ($order_id) {
    $order = Order::findByID($order_id);

    if ($order) {
        $vars = transformer_item($order, new OrderTransformer(),['user']);
    }
}

return [
    'vars' => $vars
];