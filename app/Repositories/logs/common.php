<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-12
 * Time: 05:37
 */
use App\Models\UserMoneyLog;
use AppView\Helpers\TransformerPaginatorAdapter;

$vars = [];
$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;
$user_id = input('user_id');
$build = UserMoneyLog::where('uml_type',$type)
    ->where('uml_user_id',$user_id);

$items = $build->order_by('uml_id', 'DESC')
    ->pagination($page, $page_size)
    ->all();

$total = $build->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($items->count()) {
    $vars = transformer_collection_paginator($items, new \App\Transformers\UserMoneyLogTransformer(), $paginator);
}

return [
    'vars' => $vars
];