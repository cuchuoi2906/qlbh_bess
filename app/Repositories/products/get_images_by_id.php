<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 2/26/2019
 * Time: 2:12 PM
 */

use App\Models\ProductImage;
use App\Transformers\ProductImageTransformer;

$vars = [];
$pro_id = input('pro_id') ?? 0;

$items = ProductImage::where('pri_product_id =' . $pro_id)
    ->all();

if ($items->count()) {
    $vars = transformer_collection($items, new ProductImageTransformer());
}

return [
    'vars' => $vars
];