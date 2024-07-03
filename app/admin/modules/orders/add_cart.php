<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 30/12/2020
 * Time: 11:10
 */

require_once 'inc_security.php';
checkAddEdit("add");

disable_debug_bar();

$product_id = getValue('id', 'int', 'POST');
$user_id = getValue('user_id', 'int', 'POST');
$quantity = getValue('quantity', 'int', 'POST', 1);
$add_more = getValue('add_more', 'int', 'POST');

if ($item = \App\Models\Users\UserCartAdmin::where('usc_user_id', '=', $user_id)
    ->where('usc_product_id', '=', $product_id)->first()) {
    if ($add_more) {
        $item->usc_quantity += $quantity;
    } else {
        $item->usc_quantity = $quantity;
    }
    $id = $item->update();
} else {
    $id = \App\Models\Users\UserCartAdmin::insert([
        'usc_user_id' => $user_id,
        'usc_product_id' => $product_id,
        'usc_quantity' => (int)$quantity
    ]);
}
echo $id;

