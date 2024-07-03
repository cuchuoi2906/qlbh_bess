<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 1/15/20
 * Time: 06:01
 */

$repo = 'money_add_request_notify';

return [
    $repo . '/create' => [
        'title' => 'Tạo thông báo nạp tiền',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'type' => [
                'title' => 'Loại yêu cầu',
                'comment' => '(0 = Yêu cầu nạp tiền, 1 = Yêu cầu thanh toán hóa đơn)',
                'default' => 0,
                'rule' => 'integer'
            ],
            'account_name' => [
                'title' => 'Tên chủ tài khoản',
                'rule' => 'required'
            ],
            'account_number' => [
                'title' => 'Số tài khoản',
                'rule' => 'required'
            ],
            'bank_name' => [
                'title' => 'Tên ngân hàng',
                'rule' => 'required'
            ],
            'trade_code' => [
                'title' => 'Mã giao dịch',
                'rule' => 'required'
            ],
            'money' => [
                'title' => 'Số tiền',
                'rule' => 'integer'
            ],
            'order_code' => [
                'title' => 'Mã đơn hàng',
                'rule' => ''
            ],
            'image' => [
                'title' => 'Ảnh giao dịch',
                'rule' => ''
            ]
        ]
    ]
];