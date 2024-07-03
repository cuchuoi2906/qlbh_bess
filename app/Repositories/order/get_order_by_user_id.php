<?php

use App\Models\Order;

$vars = [];

$user_id = input('user_id');
$user = App\Models\Users\Users::findByID($user_id);
if (!$user) {
    api_404();
}

$orderModel = Order::where('ord_user_id', $user->id);
$orderModel->order_by('ord_successed_at', 'DESC')
    ->where('ord_status_code', '=', Order::SUCCESS);

if (input('start_date')) {
    $orderModel->where('ord_successed_at', '>=', input('start_date') . ' 00:00:00');
}

if (input('end_date')) {
    $orderModel->where('ord_successed_at', '<=', input('end_date') . ' 23:59:59');
}

$modelMoney = clone $orderModel;

$items = $orderModel->pagination(input('page') ?? 1)->all();
$total = $orderModel->count();

$total_money = $modelMoney->sum('ord_amount')->first();

$paginator = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, input('page') ?? 1, input('page_size') ?? 10);

if ($items->count()) {
    $vars = transformer_collection_paginator($items, new \App\Transformers\OrderTransformer(), $paginator, ['products'], [
        'total_money' => (int)$total_money->total,
        'total_money_display' => number_format((int)$total_money->total),
    ]);
}

return [
    'vars' => $vars
];