<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 01:13
 */

return [
    'user_banks/index' => [
        'title' => 'Danh sách tài khoản ngân hàng của user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'user_banks/add' => [
        'title' => 'Thêm mới tài khoản ngân hàng',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'bank_name' => [
                'title' => 'Tên ngân hàng',
                'rule' => 'required'
            ],
            'account_name' => [
                'title' => 'Tên chủ tài khoản',
                'rule' => 'required'
            ],
            'account_number' => [
                'title' => 'Số tài khoản',
                'rule' => 'required'
            ],
            'branch' => [
                'title' => 'Chi nhánh',
                'rule' => 'required'
            ],
        ]
    ],
    'user_banks/edit' => [
        'title' => 'Thêm mới tài khoản ngân hàng',
        'input' => [
            'id' => [
                'title' => 'Bank id',
                'rule' => 'required|integer'
            ],
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'bank_name' => [
                'title' => 'Tên ngân hàng',
                'rule' => ''
            ],
            'account_name' => [
                'title' => 'Tên chủ tài khoản',
                'rule' => ''
            ],
            'account_number' => [
                'title' => 'Số tài khoản',
                'rule' => ''
            ],
            'branch' => [
                'title' => 'Chi nhánh',
                'rule' => ''
            ],
        ]
    ],
    'user_banks/delete' => [
        'title' => 'Xóa',
        'input' => [
            'id' => [
                'title' => 'ID',
                'rule' => 'required|integer'
            ],
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ]
];