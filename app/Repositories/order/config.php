<?php

/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-04
 * Time: 06:46
 */
return [
    'order/get_order_by_id' => [],
    'order/get_order_by_user_id' => [
        'title' => 'Lấy danh sách đơn của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [],
            'end_date' => []
        ]
    ],
    'order/post_order' => [
        'title' => 'Tạo đơn hàng',
        'input' => [
            //            'products' => [
            //                'title' => 'số lượng và id của hàng hóa',
            //                'rule' => 'required'
            //            ],
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
                'rule' => ''
            ],
            'address' => [
                'title' => 'Địa chỉ người mua',
                'rule' => 'required'
            ],
            'note' => [
                'title' => 'Ghi chú khi mua hàng',
                'rule' => ''
            ],
            'user_id' => [
                'title' => 'ID user đang đăng nhập',
                'rule' => 'required|integer'
            ],
            'admin' => [
                'title' => 'Tạo từ admin?',
                'rule' => 'integer'
            ]
        ],
    ],
    'order/post_order_v2' => [
        'title' => 'Tạo đơn hàng',
        'input' => [
            //            'products' => [
            //                'title' => 'số lượng và id của hàng hóa',
            //                'rule' => 'required'
            //            ],
            //            'name' => [
            //                'title' => 'Tên người mua',
            //                'rule' => 'required'
            //            ],
            //            'phone' => [
            //                'title' => 'Số điện thoại người mua',
            //                'rule' => ''
            //            ],
            'email' => [
                'title' => 'Email người mua',
                'rule' => ''
            ],
            'address_id' => [
                'title' => 'Địa chỉ người mua',
                'rule' => 'required|integer|min_numeric,1'
            ],
            'note' => [
                'title' => 'Ghi chú khi mua hàng',
                'rule' => ''
            ],
            'user_id' => [
                'title' => 'ID user đang đăng nhập',
                'rule' => 'required|integer'
            ],
            'admin' => [
                'title' => 'Tạo từ admin?',
                'rule' => 'integer'
            ]
        ],
    ],
    'order/post_order_v3' => [
        'title' => 'Tạo đơn hàng',
        'input' => [
            'note' => [
                'title' => 'Ghi chú khi mua hàng',
                'rule' => ''
            ],
            'user_id' => [
                'title' => 'ID user đang đăng nhập',
                'rule' => 'required|integer'
            ]
        ],
    ],
    'order/orders' => [
        'title' => 'lấy danh sách đơn hàng của user',
        'input' => [
            'user_id' => [
                'title' => 'id user',
                'rule' => 'required|integer',
            ],
            'page' => [
                'title' => 'Số trang',
                'rule' => 'integer|min_numeric,0'
            ],
            'page_size' => [
                'title' => 'Số phần tử 1 trang',
                'rule' => 'integer|min_numeric,0'
            ]
        ]
    ],
    'order/paid' => [
        'title' => 'Chuyển trạng thái đã thanh toán',
        'input' => [
            'user_id' => [
                'title' => 'user đang login',
                'rule' => 'required|integer',

            ],
            'order_id' => [
                'title' => 'ID đơn hàng',
                'rule' => 'required',
            ]

        ]
    ],
    'order/order_sub_list' => [
        'title' => 'Lấy danh sách đơn hàng của cấp dưới',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'order/delete' => [
        'title' => 'Disable 1 đơn hàng',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'id' => [
                'title' => 'Order id',
                'rule' => 'required|integer'
            ],
            'note' => [
                'title' => 'Nội dung ghi chú',
                'rule' => ''
            ]
        ]
    ],
    'order/renew' => [
        'title' => 'Disable 1 đơn hàng',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'id' => [
                'title' => 'Order id',
                'rule' => 'required|integer'
            ],
        ]
    ]
];
