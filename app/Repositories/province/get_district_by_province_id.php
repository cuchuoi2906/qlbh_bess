<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 10:11
 */

$vars = [];

$items = \App\Models\District::where('dis_province_id', input('province_id'))->all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\DistrictTransformer());
}

return [
    'vars' => $vars
];