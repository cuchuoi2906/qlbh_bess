<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 15/01/2021
 * Time: 14:11
 */

$data = [];

$address = \App\Models\Users\UserAddress::where('usa_user_id', input('user_id'))
    ->findByID(input('id'));

if (!$address) {
    throw new RuntimeException('Người dùng không có địa chỉ này', 404);
}

if (input('is_main')) {
    $data['usa_is_main'] = input('is_main');
    if (input('is_main') == 1) {
        \App\Models\Users\UserAddress::where('usa_user_id', input('user_id'))
            ->update(['usa_is_main' => 0]);
    }
}
if (input('title')) {
    $data['usa_title'] = input('title');
}
if (input('name')) {
    $data['usa_name'] = input('name');
}
if (input('phone')) {
    $data['usa_phone'] = input('phone');
}
if (input('address')) {
    $data['usa_address'] = input('address');
}
if (input('ward_id')) {
    $data['usa_ward_id'] = input('ward_id');
}
if (input('province_id')) {
    $data['usa_province_id'] = input('province_id');
}
if (input('district_id')) {
    $data['usa_district_id'] = input('district_id');
}

$affected = \App\Models\Users\UserAddress::update(
    $data,
    'usa_id = ' . (int)input('id') . ' AND usa_user_id = ' . input('user_id')
);

return [
    'vars' => $affected
];