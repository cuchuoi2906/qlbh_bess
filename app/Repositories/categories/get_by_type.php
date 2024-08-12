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
    ->fields('*, (SELECT count(pro_id) FROM products WHERE pro_category_id = categories.cat_id) count_pro_id ')
    ->where('cat_active', 1)
    ->where('cat_parent_id', '=', 0)
    ->order_by('cat_order', 'ASC')
    ->all();

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\CategoryTransformer(),['childs']);
}

return [
    'vars' => $vars
];