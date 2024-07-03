<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/23/19
 * Time: 02:17
 */

$vars = [];

$user = \App\Models\Users\Users::findByID(input('user_id'));
if ($user) {
    $vars = [
        'direct_refer' => (int)$user->total_direct_refer,
        'refer' => (int)$user->total_refer
    ];
}

return [
    'vars' => $vars
];