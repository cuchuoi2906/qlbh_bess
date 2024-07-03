<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 01/02/2021
 * Time: 11:33
 */


$user_id = input('user_id');
$product_id = input('product_id');

$user = \App\Models\Users\Users::findByID($user_id);
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

$product = \App\Models\Product::findByID($product_id);
if (!$product) {
    throw new RuntimeException('Sản phẩm không tồn tại', 404);
}

//Check xem like chưa
$check = \App\Models\ProductLiked::where('prl_product_id', $product->id)
    ->where('prl_user_id', $user->id)
    ->first();

if (!$check) {
    throw new RuntimeException('Bạn chưa thích sản phẩm này', 400);
}

\App\Models\ProductLiked::where('prl_user_id', $user_id)
    ->where('prl_product_id', $product_id)
    ->delete();

//Đếm số người like sản phẩm để trả về
$total = \App\Models\ProductLiked::where('prl_product_id', $product_id)->count();
$product->pro_total_liked = $total;
$product->update();

return [
    'vars' => transformer_item($product, new \App\Transformers\ProductTransformer(), ['images', 'category', 'pricePolicies'])
];