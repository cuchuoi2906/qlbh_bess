<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 09/05/2021
 * Time: 16:34
 */

use App\Models\Order;

$vars = [];
$campaign = \App\Models\TopRacingCampaign::findByID(input('campaign_id'));
if (!$campaign) {
    api_404('Không tìm thấy chiến dịch đua top');
}

$products = $campaign->products->lists('pro_id');
//dd($products);
$start = date('Y-m-d', $campaign->start);

$type = $campaign->type;

$model = \App\Models\BestTeam::fields('*')
    ->with(['user'])
    ->inner_join('users', 'use_id = bes_user_id AND use_premium = 0')
    ->sum('bes_point', 'total_point')
    ->sum('bes_money', 'total_money')
    ->sum('bes_money_point', 'total_money_point')
    ->where('bes_product_id', (array)$products)
    ->where('bes_date', '>=', $start)
    ->group_by('bes_user_id')
    ->order_by('total_money_point', 'DESC')
    ->pagination(input('page') ?? 1, input('page_size') ?? 10);

if ($campaign->end) {
    $model->where('bes_date', '<=', date('Y-m-d', $campaign->end));
}

if ($type == \App\Models\TopRacingCampaign::TYPE_TEAM) {
    $model->where('bes_type', \App\Models\BestTeam::TYPE_TEAM_PRODUCT_QUANTITY);
} else {
    $model->where('bes_type', \App\Models\BestTeam::TYPE_OWN_PRODUCT_QUANTITY);
    // $model->inner_join('orders', 'ord_user_id = bes_user_id AND ord_status_code = \''. Order::SUCCESS .'\'')
            // ->inner_join('order_products', 'ord_id = orp_ord_id AND orp_product_id = bes_product_id');
}

$items = $model->all();
$total = $model->count();

$pagination = new VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, input('page') ?? 1, input('page_size') ?? 10);

if ($items->count()) {
    $vars = transformer_collection_paginator($items, new \App\Transformers\BestTeamUserTransformer(), $pagination);
}


return [
    'vars' => $vars
];
