<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/22/19
 * Time: 14:03
 */

$vars = [];


$items = \App\Models\Users\Users::fields('*')->sum('orc_amount', 'total_amount')
    ->inner_join('order_commissions', 'use_id = orc_user_id 
AND orc_is_owner = 1
AND orc_status_code = \'' . \App\Models\OrderCommission::STATUS_SUCCESS . '\'')
    ->inner_join('orders', 'orc_order_id = ord_id AND ord_status_code = \'' . \App\Models\Order::SUCCESS . '\'')
    ->where('use_active', 1)
    ->group_by('use_id')
    ->order_by('total_amount', 'DESC')
    ->limit(input('limit') ?? 10)
    ->all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars
];