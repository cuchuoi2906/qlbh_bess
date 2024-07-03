<?php

/**
 * Created by huanvv
 * Date: 2/28/2012
 * Time: 1:28 PM
 */

use \App\Models\Post;
use \App\Transformers\PostTransformer;
use VatGia\Helpers\Transformer\TransformerPaginatorAdapter;

$vars = [];
$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;
$category_id = input('pos_category_id') ?? 0;
$type = replaceMQ(input('type')) ?? '';

$conditions = 'pos_active = 1';

if ($category_id) {
    $conditions .= ' AND pos_category_id = ' . (int)$category_id;
}

if ($type !== '') {
    $conditions .= " AND pos_type = '$type'";
}

$items = Post::with(['category'])
    ->where($conditions)
    ->order_by('pos_id', 'DESC')
    ->pagination($page, $page_size)
    ->all();

$total = Post::where($conditions)->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($items->count() > 0) {
    $vars = transformer_collection_paginator($items, new PostTransformer(), $paginator, ['category']);
}

return [
    'vars' => $vars
];