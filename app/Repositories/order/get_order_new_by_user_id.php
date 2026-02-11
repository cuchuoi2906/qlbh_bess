<?php

use App\Models\Order;

$vars = [];

$user_id = input('user_id');
$user = App\Models\Users\Users::findByID($user_id);
if (!$user) {
    api_404();
}

$orderModel = Order::where('ord_user_id', $user->id);
$orderModel->order_by('ord_updated_at', 'DESC')
    ->where('ord_status_code', '=', Order::NEW);
$items = $orderModel->all(false,1);
$paginator = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, input('page') ?? 1, input('page_size') ?? 10);

if ($items) {
    $vars = transformer_collection($items, new \App\Transformers\OrderTransformer(),['products']);
}

return [
    'vars' => $vars
];