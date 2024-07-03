<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/10/18
 * Time: 01:35
 */

use App\Models\Product;
use App\Transformers\ProductTransformer;
use VatGia\Helpers\Transformer\TransformerPaginatorAdapter;

$vars = [];

$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;
$category_id = input('category_id') ?? 0;


$keyword = input('keyword') ?? '';
$is_hot = input('is_hot') ?? -1;

$conditions = 'pro_active = 1';
if ((int)app('auth')->u_id === 1234) {
    $conditions = '1 ';
}

if ($category_id) {
    $category = App\Models\Categories\Category::findByID($category_id);
    if (!$category) {
        throw new RuntimeException('Danh mục không tồn tại', 404);
    }
    $field = 'pro_category_id';
    if ($category->type == 'BRAND') {
        $field = 'pro_brand_id';
    }
    $conditions .= ' AND ' . $field . ' = ' . (int)$category_id;
}

if ($keyword) {
    $conditions .= ' AND pro_name_vn LIKE \'%' . $keyword . '%\'';
}

if ($is_hot >= 0) {
    $conditions .= ' AND pro_is_hot = ' . $is_hot;
}


$itemsModel = Product::with(['images', 'category', 'pricePolicies'])
    ->where($conditions)
    ->pagination($page, $page_size);

if (input('sort_by')) {
    $itemsModel->order_by('pro_' . input('sort_by'), input('sort_type'));
} else {
    $itemsModel->order_by('pro_order', 'DESC')
        ->order_by('pro_id', 'DESC');
}

$items = $itemsModel->all();
$total = Product::where($conditions)->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($items->count()) {
    $vars = transformer_collection_paginator($items, new ProductTransformer(), $paginator, ['images', 'category', 'pricePolicies']);
}

return [
    'vars' => $vars
];
