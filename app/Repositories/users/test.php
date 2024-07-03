<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-03
 * Time: 16:58
 */
$vars = false;


$user = \App\Models\Users\Users::find(1)->toArray();
if (!$user) {
    throw new Exception('Không có user nào gắn với số điện thoại ' . input('phone'));
}

//dd($user);
$vars['user'] = $user;

return [
    'vars' => $vars
];