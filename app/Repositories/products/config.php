<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 2/26/2019
 * Time: 2:11 PM
 */

return [
    'products/index' => [
        'title' => 'Danh sách sản phẩm',
        'input' => [
            'category_id' => [
                'title' => 'Danh mục sản phẩm',
                'rule' => 'integer'
            ],
            'keyword' => [
                'title' => 'Từ khóa tìm kiếm',
                'rule' => ''
            ],
            'is_hot' => [
                'title' => 'Lấy danh sách sản phẩm hot',
                'rule' => 'integer'
            ],
            'sort_by' => [
                'title' => 'Trường sắp xếp'
            ],
            'sort_type' => [
                'title' => 'Kiểu sắp xếp',
                'default' => 'DESC'
            ]
        ]
    ],

    'products/get_images_by_id' => [
        'title' => 'Danh sách hình ảnh sản phẩm',
        'input' => [
            'type' => [
                'title' => 'Danh sách hình ảnh sản phẩm',
                'rule' => ''
            ]
        ]
    ],

    'products/get_by_id' => [
        'title' => 'Chi tiết sản phẩm theo id',
        'input' => [
            'type' => [
                'title' => 'Chi tiết sản phẩm theo id',
                'rule' => ''
            ]
        ]
    ],

    'products/like' => [
        'title' => 'Like 1 sản phẩm',
        'input' => [
            'user_id' => [
                'rule' => 'required|integer'
            ],
            'product_id' => [
                'rule' => 'required|integer'
            ]
        ]
    ],
    'products/unlike' => [
        'title' => 'Bỏ Like 1 sản phẩm',
        'input' => [
            'user_id' => [
                'rule' => 'required|integer'
            ],
            'product_id' => [
                'rule' => 'required|integer'
            ]
        ]
    ],
    'products/liked' => [
        'title' => 'Danh sách sản phẩm 1 user đã like',
        'input' => [
            'user_id' => [
                'rule' => 'required|integer'
            ],
        ]
    ],
];