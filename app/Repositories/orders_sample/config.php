<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 01:52
 */

return [
    'orders/index' => [
        'title' => 'Lấy danh sách order',
        'input' => [
            'user_id' => [
                'title' => 'Lọc danh sách đơn theo user_id',
                'rule' => 'integer'
            ],
            'start_time' => [
                'title' => 'Lọc theo thời gian bắt đầu',
                'rule' => 'date'
            ],
            'end_time' => [
                'title' => 'Lọc theo thời gian kết thúc',
                'rule' => 'date'
            ],
            'status' => [
                'title' => 'Lọc theo trạng thái đơn',
                'rule' => ''
            ],
            'payment_status' => [
                'title' => 'Lọc theo trạng thái thanh toán',
                'rule' => ''
            ]
        ]
    ],
    'orders/create' => [
        'title' => 'Tạo đơn hàng',
        'input' => [
            'name' => [
                'title' => 'Tên người mua',
                'rule' => 'required'
            ],
            'phone' => [
                'title' => 'Số điện thoại người mua',
                'rule' => 'required|phone_number'
            ],
            'email' => [
                'title' => 'Email người mua',
                'rule' => 'required|valid_email'
            ],
            'address' => [
                'title' => 'Địa chỉ người mua',
                'rule' => 'required|street_address'
            ],
            'note' => [
                'title' => 'Ghi chú khi mua hàng',
                'rule' => ''
            ],
            'products' => [
                'title' => 'Sản phẩm',
                'rule' => 'required'
            ]
        ]
    ],
    'orders/detail' => [
        'title' => 'Lấy chi tiết đơn hàng',
        'input' => [
            'id' => [
                'title' => 'ID đơn hàng',
                'rule' => 'integer'
            ],
            'code' => [
                'title' => 'Mã đơn hàng',
                'rule' => ''
            ]
        ]
    ]
];