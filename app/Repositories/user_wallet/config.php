<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 19:04
 */

return [
    'user_wallet/transfer' => [
        'title' => 'Chuyển tiền từ ví hoa hồng sang ví nạp',
        'input' => [
            'user_id' => [
                'title' => 'User ID',
                'rule' => 'required|integer'
            ],
            'money' => [
                'title' => 'Số tiền cần chuyển',
                'rule' => 'required|float|min_numeric,1000'
            ]
        ]
    ],
];