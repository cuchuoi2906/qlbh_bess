<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 01:52
 */

use App\Models\Order;

$vars = [];
$user_id = input('user_id') ?: '';

$conditions = '1';
$items = Order::with(['products', 'logs']);
if ($user_id) {
    $items->where('ord_user_id', $user_id);
}
$items = $items->pagination(getValue('page', 'int', 'GET'), $limit ?? 10)
    ->order_by('ord_id','DESC')
    ->all();
//dd($items);
//$total = Order::where($conditions)->count();
//
//$paginator = new \AppView\Helpers\TransformerPaginatorAdapter($total, $page, $page_size);
//dd($items);
if ($items->count() > 0) {
    $vars = transformer_collection($items, new \App\Transformers\OrderTransformer());
}

return [
    'vars' => $vars
];