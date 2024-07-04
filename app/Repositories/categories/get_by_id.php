<?php

use App\Models\Categories\Category;
use App\Transformers\CategoryTransformer;

$vars = null;

$cate_id = input('id') ?? 0;

if ($cate_id) {
    $category = Category::findByID($cate_id);
    if ($category) {
        $vars = transformer_item($category, new CategoryTransformer());
    }
}

return [
    'vars' => $vars
];