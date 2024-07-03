<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/28/19
 * Time: 00:04
 */

$vars = [];

$items = \App\Models\Users\Users::where('use_active', 1)
    ->order_by('use_id', 'DESC')
    ->limit(input('limit') ?? 10)
    ->all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars
];