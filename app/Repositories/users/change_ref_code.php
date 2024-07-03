<?php

/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 9/9/19
 * Time: 15:13
 */

//Check user
$affected = false;
$user = \App\Models\Users\Users::findByID((int)input('user_id'));

if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

if (!preg_match('/^[a-zA-Z0-9]*$/', input('code'))) // '/[^a-z\d]/i' should also work.
{
    throw new RuntimeException('Mã giới thiệu chỉ được bao gồm chữ và số', 400);
}

$ref = \App\Models\Users\Users::where('use_login = \'' . input('code') . '\' OR use_id = \'' . input('code') . '\' OR use_referer_code = \'' . input('code') . '\'')
    ->first();
if ($ref) {
    throw new RuntimeException('Mã giới thiệu đã có người sử dụng', 400);
}

$user->referer_code  = input('code');
$affected = $user->update();

return [
    'vars' => $affected
];
