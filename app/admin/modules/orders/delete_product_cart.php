<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 30/12/2020
 * Time: 14:05
 */

require_once 'inc_security.php';
checkAddEdit("add");

disable_debug_bar();

$product_id = getValue('id', 'int', 'GET');
$user_id = getValue('user_id');

\App\Models\Users\UserCartAdmin::where('usc_user_id', '=', $user_id)
    ->where('usc_product_id', '=', $product_id)
    ->delete();