<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/23/19
 * Time: 01:42
 */

return [
    'statistic/member' => [
        'title' => 'Thống kê số thành viên của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'statistic/all_member' => [
        'title' => 'Thống kê số thành viên của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'statistic/point_group_member' => [
        'title' => 'Thống kê point của đội nhóm',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'statistic/money' => [
        'title' => 'Thông kê doanh số của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'statistic/order' => [
        'title' => 'Thông kê đơn hàng của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_time' => [
                'title' => 'Thời gian bắt đầu'
            ],
        ]
    ],
    'statistic/owner_order' => [
        'title' => 'Thông kê đơn hàng ca nhan của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
        ]
    ],
    'statistic/direct_order' => [
        'title' => 'Thông kê đơn hàng truc tiep của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
        ]
    ],
    'statistic/all_order' => [
        'title' => 'Thông kê đơn hàng he thong của 1 user',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
        ]
    ],
    'statistic/commission' => [
        'title' => 'Thông kê điểm tích lũy',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_time' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_time' => [
                'title' => 'Thời gian kết thúc'
            ],
            'type' => [
                'title' => 'Loại hoa hồng (0: Toàn hệ thống| 1: Cá nhân| 2: Đại lý trực tiếp)'
            ]
        ]
    ],
    'statistic/owner_commission' => [
        'title' => 'Thông kê điểm tích lũy ca nhan',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_date' => [
                'title' => 'Thời gian kết thúc'
            ],
        ]
    ],
    'statistic/direct_commission' => [
        'title' => 'Thông kê điểm tích lũy ca nhan',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_date' => [
                'title' => 'Thời gian kết thúc'
            ],
        ]
    ],
    'statistic/all_commission' => [
        'title' => 'Thông kê điểm tích lũy ca nhan',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'start_date' => [
                'title' => 'Thời gian bắt đầu'
            ],
            'end_date' => [
                'title' => 'Thời gian kết thúc'
            ],
        ]
    ],
];