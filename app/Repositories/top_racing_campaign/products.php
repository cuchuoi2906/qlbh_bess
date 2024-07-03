<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 09/05/2021
 * Time: 21:21
 */

$vars = [];

$model = \App\Models\Product::inner_join('top_racing_campaign_product', 'trcp_product_id = pro_id')
    ->inner_join('top_racing_campaign', 'trcp_campaign_id = trc_id')
    ->where('trc_active', 1)
//    ->where('trc_start', '<=', time())
    ->where('trc_end = 0 OR trc_end >= ' . time())
    ->pagination();

$items = $model->all();
$total = $model->distinct('pro_id')->count();


if ($items->count()) {
    $pagination = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, input('page') ?? 1, input('page_size') ?? 10);
    $vars = transformer_collection_paginator($items, new \App\Transformers\ProductTransformer(), $pagination, ['images', 'category', 'pricePolicies']);
}

return [
    'vars' => $vars
];