<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/29/18
 * Time: 22:36
 */

$vars = false;

$id = input('id') ?? 0;
$code = input('code') ?? '';
$order = false;
if ($id) {
    $order = \App\Models\Order::where('ord_id', $id)->first();
} elseif (strlen($code) > 0) {
    $order = \App\Models\Order::where('ord_code', $code)->first();
}

if ($order) {
    $vars = transformer_item($order, new \App\Transformers\OrderTransformer(), ['products']);
}

return [
    'vars' => $vars
];
