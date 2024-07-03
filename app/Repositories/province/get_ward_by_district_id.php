<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 10:12
 */

$vars = [];

$items = \App\Models\Ward::where('war_district_id', input('district_id'))->all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\WardTransformer());
}

return [
    'vars' => $vars
];