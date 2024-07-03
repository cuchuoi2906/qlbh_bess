<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-12
 * Time: 05:35
 */
return [
    'logs/moneyadds' => [
        'title' => 'Lấy danh sách logs tài khoản nạp tiền của 1 users',
        'input' => [
            'user_id' => [
                'title' => 'user_id cần lấy',
                'rule' => 'required'
            ],
        ],
    ],
    'logs/commissions' => [
        'title' => 'Lấy danh sách logs tài khoản Hoa hồng của 1 users',
        'input' => [
            'user_id' => [
                'title' => 'user_id cần lấy',
                'rule' => 'required'
            ],
        ],
    ],
];