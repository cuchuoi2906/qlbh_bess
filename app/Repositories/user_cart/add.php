<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 13:54
 */

use App\Models\Users\UserCart;
use App\Models\Users\UserCartAdmin;

$affected = 0;

if (input('admin_id')) {
    $model = UserCartAdmin::class;
} else {
    $model = UserCart::class;
}

//Check sản phẩm tồn tại
$product = \App\Models\Product::findByID(input('product_id'));
if ($product) {
    $is_add_more = input('is_add_more') ?? 1;

    if (!$is_add_more && !input('quantity')) {
        $model::where('usc_user_id', input('user_id'))
            ->where('usc_product_id', input('product_id'))
            ->delete();
    } else {
        if ($is_add_more) {
            $update_data = [
                'usc_quantity' => ['usc_quantity + %d', (int)input('quantity')]
            ];
        } else {
            $update_data = [
                'usc_quantity' => input('quantity')
            ];
        }
        $affected = $model::insertUpdate([
            'usc_user_id' => (int)input('user_id'),
            'usc_product_id' => (int)input('product_id'),
            'usc_quantity' => (int)input('quantity')
        ], $update_data);
    }
}

return [
    'vars' => $affected
];
