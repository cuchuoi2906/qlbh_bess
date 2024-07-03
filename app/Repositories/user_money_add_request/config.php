<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-19
 * Time: 14:48
 */
return [
    'user_money_add_request/create' => [
        'title' => 'Tạo yêu cầu nạp tiền',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'amount' => [
                'title' => 'Số tiền nạp',
                'rule' => 'required|float|min_numeric,1000'
            ],
            'bank_id' => [
                'title' => 'Bank id người dùng chọn',
                'rule' => 'required|integer'
            ],
            'type' => [
                'title' => 'Loại nạp hoặc thanh toán trực tiếp',
                'rule' => 'required'
            ]
        ]
    ],
    'user_money_add_request/update_success' => [
        'title' => 'Update trạng thái request xử lý nạp tiền và thanh toán đơn hàng Online',
        'input' => [
            'request_id' => [
                'title' => 'Resquest id do bảo kim bắn bpn trả về',
                'rule' => 'required|integer',
            ],
            'type' => [
                'title' => 'Loại đơn hàng, xác định dựa theo order_id bảo kim trả về',
                'type' => 'required'
            ]
        ]
    ]
];