<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-08
 * Time: 06:03
 */

use App\Models\Order;
use App\Transformers\OrderTransformer;
use VatGia\Helpers\Transformer\TransformerPaginatorAdapter;

$vars = [];
$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;

$conditions = ['user_id', input('user_id')];

$end_date = input('end_date');
if (!$end_date) {
    $end_date = date('Y-m-d');
}
$sql = 'DATE(ord_created_at) <= \'' . $end_date . '\'';

if (input('start_date')) {
    $sql .= ' AND DATE(ord_created_at) >= \'' . input('start_date') . '\'';
}

$items = Order::where('ord_user_id', input('user_id'))
    ->where('ord_active', 1)
    ->where('ord_status_code', '<>', Order::CANCEL)
    ->where($sql)
    ->with(['products'])
    ->order_by('ord_id', 'DESC')
    ->pagination($page, $page_size)
    ->all();

$total = Order::where('ord_user_id', input('user_id'))->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($items->count()) {
    $vars = transformer_collection_paginator($items, new OrderTransformer(), $paginator, ['products']);
}
return [
    'vars' => $vars
];