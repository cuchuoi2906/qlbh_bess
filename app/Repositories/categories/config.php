<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/8/16
 * Time: 11:50 AM
 */

return [
    'categories/get_by_id' => [
        'title' => 'Lấy chi tiết danh mục',
        'input' => [
            'cat' => [
                'title' => 'ID danh mục',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'categories/index' => [
        'title' => 'Danh sách danh mục theo loại',
        'input' => [
            'type' => [
                'title' => 'Loại danh mục',
                'rule' => ''
            ]
        ]
    ]
];