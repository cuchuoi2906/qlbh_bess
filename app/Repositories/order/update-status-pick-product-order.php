<?php

use App\Models\OrderProduct;
$orderProduct = OrderProduct::where('orp_id', input('order_product_id'))
    ->first();
if (!$orderProduct) {
    throw new RuntimeException('Không tồn tại Sản phẩm này', 404);
}

\App\Models\OrderProduct::update([
    'orp_hapu_status_pick' => input('status')
], 'orp_id = ' . input('order_product_id'));

$vars['order_product_id'] = input('order_product_id');
return [
    'vars' => $vars
];