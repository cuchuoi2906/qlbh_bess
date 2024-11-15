<?php

use App\Models\Product;
use App\Transformers\ProductTransformer;

$vars = null;

$product_id = input('id') ?? 0;
$buy_quantity = input('buy_quantity') ?? 0;


$with = input('with') ? explode(',', input('with')) : [];

if ($product_id) {
    $product = Product::with(array_merge(['images', 'category'], $with))->findByID($product_id);
    if ($product) {
        $vars = transformer_item($product, new ProductTransformer(), array_merge(['images', 'category', 'pricePolicies'], $with));
    }
}

return [
    'vars' => $vars
];