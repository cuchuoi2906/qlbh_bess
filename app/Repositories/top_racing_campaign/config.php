<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/8/16
 * Time: 11:50 AM
 */

return [
    'top_racing_campaign/get_by_id' => [
        'title' => 'Lấy chi tiết bài viết từ ID',
        'input' => [
            'id' => [
                'title' => 'ID bài viết',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'top_racing_campaign/index' => [
        'title' => 'Danh sách bài viết',
        'input' => [
            'page' => [
                'title' => 'Số trang',
                'rule' => 'integer|min_numeric,0'
            ]
        ]
    ],
    'top_racing_campaign/top' => [
        'title' => 'Top',
        'input' => [
            'campaign_id' => [
                'title' => 'Campaign ID',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'top_racing_campaign/products' => [
        'title' => 'Products campaign',
        'input' => [
        ]
    ],
];