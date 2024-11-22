<?php

//Check user
$affected = false;
$user = \App\Models\Users\Users::findByID((int)input('id'));
$vars = [];
if ($user) {
    
    if (!$_REQUEST['ship_name']) {
        throw new RuntimeException('Bạn phải hoàn thiện thông tin Họ tên', 400);
    }
    if (!$_REQUEST['ship_address']) {
        throw new RuntimeException('Bạn phải hoàn thiện thông tin Địa chỉ', 400);
    }
    
    $user->use_name = $_REQUEST['ship_name'];
    $user->use_address_register = $_REQUEST['ship_address'];
    $affected = $user->update();
}

if ($affected) {
    $user = \App\Models\Users\Users::findByID((int)input('id'));
    $vars = transformer_item($user, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars
];