<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 3/2/2019
 * Time: 8:58 AM
 */

use \App\Models\Users\Users;
use \App\Transformers\UserTransformer;
use VatGia\Helpers\Transformer\TransformerPaginatorAdapter;

$vars = [];
$id = input('id') ?? 0;
$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;

$conditions = "use_referral_id = $id AND use_active = 1";

$collaborators = Users::where($conditions)
    ->fields('users.*, COUNT(ord_id) AS total_order, SUM(IF(ord_status_code = \'' . \App\Models\Order::SUCCESS . '\', 1, 0)) AS total_order_success')
    ->left_join('orders', 'ord_user_id = use_id')
    ->order_by('use_id', 'DESC')
    ->group_by('use_id')
    ->pagination($page, $page_size)
    ->all();

$total = Users::where($conditions)->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($collaborators) {
    $vars = transformer_collection_paginator($collaborators, new UserTransformer(), $paginator);
}

return [
    'vars' => $vars
];