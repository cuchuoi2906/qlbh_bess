<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 13:50
 */

return [
    'user_cart/add' => [
        'title' => 'Thêm sản phẩm vào giỏ hàng',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'product_id' => [
                'title' => 'Product ID',
                'rule' => 'required|integer'
            ],
            'quantity' => [
                'title' => 'Số lượng',
                'rule' => 'integer'
            ],
            'is_add_more' => [
                'title' => 'Là thêm mói số lượng hay tổng số lượng',
                'rule' => 'integer',
                'default' => 1
            ]
        ]
    ],
    'user_cart/index' => [
        'title' => 'Danh sách sản phẩm trong giỏ',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => ''
            ],
            'admin' => [
                'title' => 'Thêm từ admin'
            ]
        ]
    ]
];