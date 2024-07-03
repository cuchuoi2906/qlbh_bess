<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$vars = [];
$items = \App\Models\Registration::where('re_email', input('email'))
    ->count();
if(intval($items) >0){
    $vars['suscess'] = 0;
    $vars['message'] = 'Email đã tồn tại';
    return [
        'vars' => $vars
    ];
}
$items = \App\Models\Registration::where('re_phone', input('phone_number'))
    ->count();
if(intval($items) >0){
    $vars['suscess'] = 0;
    $vars['message'] = 'Email đã tồn tại';
    return [
        'vars' => $vars
    ];
}
$user_dky = [
    're_name' => input('name'),
    're_phone' => input('phone_number'),
    're_email' => input('email'),
    're_address' => input('your_addrest'),
    're_comment' => input('your_message')
];
$user_id = \App\Models\Registration::insert($user_dky);
if($user_id > 0){
    $vars['suscess'] = 1;
    $vars['message'] = 'Đăng ký thành công. Chúng tôi sẽ sớm liên hệ với bạn!';
    return [
        'vars' => $vars
    ];
}
die;
