<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/24/19
 * Time: 23:03
 */

$vars = [];

$user = \App\Models\Users\Users::findByID((int)input('id'));
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}
if ($user) {

    $vars = transformer_item($user, new \App\Transformers\UserTransformer(), getValue('with', 'str') . ',parent', [
        'direct' => [
            'total' => $user->use_total_direct_refer,
            'total_display' => number_format($user->use_total_direct_refer)
        ],
        'users_all' => [
            'total' => $user->use_total_refer,
            'total_display' => number_format($user->use_total_refer)
        ]
    ]);
}

return [
    'vars' => $vars
];