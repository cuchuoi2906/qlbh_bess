<?php

use App\Models\OrderProduct;
$orderProduct = OrderProduct::where('orp_id', input('order_product_id'))
    ->first();

if (!$orderProduct) {
    throw new RuntimeException('Không tồn tại Sản phẩm này', 404);
}
if(input('status') == 1){
    $note = $orderProduct->orp_check_hapu_note;
}else{
    $note = input('note');
}
\App\Models\OrderProduct::update([
    'orp_check_hapu_status' => input('status'),
    'orp_check_hapu_note' => $note,
], 'orp_id = ' . input('order_product_id'));

$vars['order_product_id'] = input('order_product_id');
return [
    'vars' => $vars
];