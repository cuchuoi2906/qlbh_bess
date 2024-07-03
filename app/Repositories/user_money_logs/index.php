<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 21:11
 */

$vars = [];

$user = \App\Models\Users\Users::findByID(input('user_id'));
if ($user) {


    $model = \App\Models\UserMoneyLog::where('uml_user_id', (int)input('user_id'));
    //$model->where('uml_amount','>',0);
    if (!empty(input('type')) && input('type') != '' && array_key_exists(input('type'), \App\Models\UserMoneyLog::types())) {
        $model->whereIn('uml_type', input('type'));
    }
    if (input('source_type') ?? false ) {
        $model->where('uml_source_type', input('source_type'));
    }

    $items = $model->pagination(input('page') ?? 1, input('page_size') ?? 5)
        ->order_by('uml_created_at', 'DESC')
        ->all();

    if ($items->count()) {
        $total = $model->count();
        $paginator = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, input('page') ?? 1, input('page_size') ?? 5);

        $vars = transformer_collection_paginator($items, new \App\Transformers\UserMoneyLogTransformer(), $paginator);
    }
}

return [
    'vars' => $vars
];