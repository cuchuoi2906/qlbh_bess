<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 2/28/2019
 * Time: 9:28 AM
 */

return [
    'settings_website/index' => [
        'title' => 'Danh sách tất cả settings_website',
        'input' => [
        ]
    ],
    'settings_website/get_by_key' =>[
        'title' => 'Lấy one row theo key',
        'input' => [
            'key' => [
                'title' => 'Key muốn lấy giá trị',
                'rule' => 'required'
            ]
        ]
    ]
];