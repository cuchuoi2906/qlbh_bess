<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 21:10
 */

return [
    'user_money_logs/index' => [
        'title' => 'Lịch sử tiền user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'type' => [
                'title' => 'Loại log',
                'rule' => ''
            ],
            'page' => [
                'title' => 'Trang cần lấy',
                'rule' => 'integer|min_numeric,1',
                'default' => 1
            ],
            'page_size' => [
                'title' => 'Số bản ghi / trang',
                'rule' => 'integer|min_numeric,1',
                'default' => 5
            ]
        ]
    ]
];