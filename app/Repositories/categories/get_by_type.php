<?php
/**
 * Created by vatgia-framework.
 * Date: 6/27/2017
 * Time: 11:39 AM
 *
 * @todo Lấy danh sách danh mục
 */

$vars = [];

$items = \App\Models\Categories\Category::where('cat_type', input('type'))
    ->where('cat_active', 1)
    ->order_by('cat_parent_id', 'ASC')
    ->all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\CategoryTransformer());
}

return [
    'vars' => $vars
];