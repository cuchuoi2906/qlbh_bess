<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 01:21
 */

$vars = [];

$user = \App\Models\Users\Users::findByID(input('user_id'));
if ($user) {
    $banks = $user->banks ?? [];
    if ($banks) {
        $vars = transformer_collection($banks, new \App\Transformers\UserBankTransformer());
    }
}

return [
    'vars' => $vars
];