<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 09:58
 */

$vars = [];

$items = \App\Models\Province::all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\ProvinceTransformer());
}

return [
    'vars' => $vars
];