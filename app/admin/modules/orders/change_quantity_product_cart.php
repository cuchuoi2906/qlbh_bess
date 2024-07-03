<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 30/12/2020
 * Time: 14:10
 */

require_once 'inc_security.php';
checkAddEdit("add");

disable_debug_bar();

$product_id = getValue('id');
$user_id = getValue('user_id');
$quantity = getValue('quantity');

if ($quantity <= 0) {
    \App\Models\Users\UserCartAdmin::where('usc_product_id', '=', $product_id)
        ->where('usc_user_id', '=', $user_id)
        ->delete();
} else {
    \App\Models\Users\UserCartAdmin::where('usc_product_id', '=', $product_id)
        ->where('usc_user_id', '=', $user_id)
        ->update([
            'usc_quantity' => (int)$quantity
        ]);
}

