<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/8/16
 * Time: 11:50 AM
 */

return [
    'notifications/index' => [
        'title' => 'Danh sách thông báo',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'type' => [
                'title' => 'Danh sách thông báo',
                'rule' => ''
            ],
            'read' => [
                'title' => 'Đánh dấu tát cả tin nhắn là đã đọc',
                'rule' => 'integer'
            ]
        ]
    ],
    'notifications/read' => [
        'title' => 'Đánh dấu 1 thông báo là đã đọc',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'notification_id' => [
                'title' => 'ID thông báo',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'notifications/push' => [
        'title' => 'Gửi notify cho 1 hoặc nhiều user',
        'input' => [
            'user_ids' => [
                'title' => 'User id',
                'rule' => 'required'
            ],
            'title' => [
                'title' => 'Tiêu đề thông báo',
                'rule' => 'required'
            ],
            'content' => [
                'title' => 'Nội dung thông báo',
                'rule' => 'required'
            ],
            'type' => [
                'title' => 'Loại thông báo',
                'rule' => ''
            ],
        ]
    ],
];