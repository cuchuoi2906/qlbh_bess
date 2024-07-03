<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 09:53
 */

return [
    'province/index' => [
        'title' => 'Lấy danh sách tỉnh thành',
        'input' => [

        ]
    ],
    'province/get_district_by_province_id' => [
        'title' => 'Lấy danh sách quận huyện 1 tỉnh thành',
        'input' => [
            'province_id' => [
                'title' => 'Mã tỉnh thành',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'province/get_ward_by_district_id' => [
        'title' => 'Lấy danh sách phường xã của 1 quận huyện',
        'input' => [
            'district_id' => [
                'title' => 'Mã quận huyện',
                'rule' => 'required|integer'
            ]
        ]
    ]
];