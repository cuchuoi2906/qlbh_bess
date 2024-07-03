<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/1/19
 * Time: 23:20
 */

$vars = false;

$user = \App\Models\Users\Users::where('use_login', input('phone'))->find();
if (!$user) {
    throw new Exception('Không có user nào gắn với số điện thoại ' . input('phone'), 400);
}

if (!$user->forgot_password_confirm_code || !input('code') || $user->forgot_password_confirm_code != input('code')) {
    throw new Exception('Mã xác thực không đúng', 400);
}

$password = input('password');
$user->use_password = md5($password);
$user->use_forgot_password_confirm_code = '';
$vars = $user->update();

return [
    'vars' => $vars
];