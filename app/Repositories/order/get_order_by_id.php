<?php
use App\Transformers\ProductTransformer;
use App\Models\Order;

$vars = [];

$order_id = input('id') ?? 0;

if ($order_id) {
    $order = Order::findByID($order_id);

    if ($order) {
        $vars = transformer_item($product, new ProductTransformer(), ['images', 'category']);
    }
}

return [
    'vars' => $vars
];