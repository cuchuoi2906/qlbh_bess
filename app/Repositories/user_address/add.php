<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 01:58
 */

$id = \App\Models\Users\UserAddress::insert([
    'usa_user_id' => (int)input('user_id'),
    'usa_is_main' => (int)input('is_main'),
    'usa_title' => input('title'),
    'usa_name' => input('name'),
    'usa_phone' => input('phone'),
    'usa_address' => input('address'),
    'usa_ward_id' => input('ward_id'),
    'usa_district_id' => input('district_id'),
    'usa_province_id' => input('province_id'),
]);

return [
    'vars' => $id
];