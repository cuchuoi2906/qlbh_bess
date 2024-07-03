<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 01/02/2021
 * Time: 14:07
 */

$vars = [];
$user_id = input('user_id');
$user = \App\Models\Users\Users::findByID($user_id);
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

$page = input('page') ?? 1;

$items = \App\Models\Product::inner_join('product_liked', 'pro_id = prl_product_id')
    ->where('prl_user_id', $user->id)
    ->pagination($page, 10)
    ->all();

$total = \App\Models\Product::inner_join('product_liked', 'pro_id = prl_product_id')
    ->where('prl_user_id', $user->id)
    ->count();

$pagination = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, $page, 10);

if ($items->count()) {
    $vars = transformer_collection_paginator($items, new \App\Transformers\ProductTransformer(), $pagination, [
//        'images'
        'pricePolicies'
    ]);
}

return [
    'vars' => $vars
];