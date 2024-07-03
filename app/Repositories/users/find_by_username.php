<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/17/19
 * Time: 20:48
 */

$vars = [];

$username = replaceMQ(input('username'));

$user = \App\Models\Users\Users::where('(
use_loginname = \'' . $username . '\' OR use_email = \'' . $username . '\' OR use_phone = \'' . $username . '\'
)');
if (input('password')) {
    $user->where('use_password', md5(input('password')));
}

$user = $user->find();

if ($user) {
    $vars = transformer_item($user, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars
];