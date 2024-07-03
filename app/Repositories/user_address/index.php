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
    $address = $user->addresses ?? [];
    if ($address) {
        $vars = transformer_collection($address, new \App\Transformers\UserAddressTransformer());
    }
}

return [
    'vars' => $vars
];