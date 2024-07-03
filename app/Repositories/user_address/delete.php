<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 02:07
 */

$ok = \App\Models\Users\UserAddress::where('usa_id', input('id'))
    ->where('usa_user_id', input('user_id'))
    ->delete();

return [
    'vars' => (bool)$ok
];