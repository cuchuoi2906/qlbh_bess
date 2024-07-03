<?php

/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/8/16
 * Time: 11:50 AM
 */

return [
    'users/index' => [
        'title' => 'Danh sách người dùng',
        'input' => [
            'page' => [
                'title' => 'Số trang',
                'rule' => 'integer|min_numeric,0'
            ]
        ]
    ], 'users/login_with_facebook' => [
        'title' => 'Login / Register bằng Facebook',
        'input' => [
            'token' => [
                'title' => 'Facebook AccessToken',
                'rule' => 'required'
            ]
        ]
    ], 'users/login_with_google' => [
        'title' => 'Login / Register bằng Google',
        'input' => [
            'token' => [
                'title' => 'Google AccessToken',
                'rule' => 'required'
            ]
        ]
    ],
    'users/best_seller' => [
        'title' => 'Người có hoa hồng trực tiếp nhiều nhất',
    ],
    'users/get_by_id' => [
        'title' => 'Lấy chi tiết',
        'input' => [
            'id' => [
                'title' => 'ID',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'users/update' => [
        'title' => 'Sửa thông tin',
        'input' => [
            'id' => [
                'title' => 'ID',
                'rule' => 'required|integer'
            ],
            'name' => [
                'title' => 'Tên',
                'rule' => ''
            ],
            'email' => [
                'title' => 'Email',
                'rule' => 'valid_email'
            ],
            'avatar' => [
                'title' => 'Ảnh đại diện. Chỉ bắn tên ảnh. Ảnh lưu trong thư mục upload/images',
                'rule' => ''
            ],
            'gender' => [
                'title' => 'Giới tính. 0 = female, 1 = male',
                'rule' => 'integer|contains_list,0;1'
            ],
            'birthdays' => [
                'title' => 'Ngày sinh. bắn string lên dạng dd/mm/yyyy',
                'rule' => ''
            ],
            'address' => [
                'title' => 'Địa chỉ',
                'rule' => ''
            ],
            'identity_number' => [
                'title' => 'Số chứng mình nhân dân',
                'rule' => ''
            ],
            'identity_front_image' => [
                'title' => 'Ảnh chứng minh mặt trước. Chỉ bắn tên ảnh. Lưu lại upload/images',
                'rule' => ''
            ],
            'identity_back_image' => [
                'title' => 'Ảnh chứng minh mặt sau. Chỉ bắn tên ảnh. Lưu lại upload/images',
                'rule' => ''
            ],
            'password' => [
                'title' => 'Password',
                'rule' => ''
            ],
            'phone' => [
                'title' => 'Số điện thoại',
                'rule' => 'phone_number'
            ],
            'trade_code' => [
                'title' => 'Mã giao dịch',
                'rule' => ''
            ],
            'old_trade_code' => [
                'title' => 'Mã giao dịch cũ',
                'rule' => ''
            ]

        ]
    ],
    'users/update_ref_code' => [
        'title' => 'Update người giới thiệu',
        'input' => [
            'ref_code' => [
                'title' => 'Mã giới thiệu',
                'rule' => 'required'
            ],
            'id' => [
                'title' => 'ID',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'users/register' => [
        'title' => 'Đăng ký tài khoản',
        'input' => [
            /*'referral_code' => [
                'title' => 'Mã người giới thiệu',
                'rule' => 'required'
            ],*/
            'phone' => [
                'title' => 'Số điện thoại',
                'rule' => 'required|phone_number'
            ],
            'email' => [
                'title' => 'Email',
                'rule' => 'valid_email'
            ],
            'name' => [
                'title' => 'Tên',
                'rule' => 'required'
            ],
            'password' => [
                'title' => 'Mật khẩu',
                'rule' => 'required|min_len,6'
            ],
            're_password' => [
                'title' => 'Mật khẩu xác thực',
                'rule' => 'required|min_len,6'
            ],
            'gender' => [
                'title' => 'Giới tính',
                'rule' => 'integer',
                'default' => 0
            ]
        ]
    ],
    'users/confirm_register_code' => [
        'title' => 'Xác thực mã đăng ký',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'code' => [
                'title' => 'Mã xác thực',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'users/forgot_password' => [
        'title' => 'Quên mật khẩu',
        'input' => [
            'phone' => [
                'title' => 'Số điện thoại / Email',
                'rule' => 'required'
            ],
        ]
    ],
    'users/confirm_forgot_password_code' => [
        'title' => 'Xác nhận mã quên mật khẩu',
        'input' => [
            'code' => [
                'title' => 'Mã xác nhận',
                'rule' => 'required'
            ],
            'phone' => [
                'title' => 'Số điện thoại',
                'rule' => 'required|phone_number'
            ],
            'password' => [
                'title' => 'Mật khẩu mới',
                'rule' => 'required'
            ]
        ]
    ],
    'users/new' => [
        'title' => 'Lấy danh sách users mới nhất',
        'input' => [
            'limit' => [
                'title' => 'Số lượng user cần lấy',
                'rule' => 'integer'
            ]
        ]
    ],

    'users/get_collaborators_by_id' => [
        'title' => 'Lấy danh sách cộng tác viên bởi use_id',
        'input' => [
            'id' => [
                'title' => 'Id của user cần lấy cộng tác viên',
                'rule' => 'integer'
            ]
        ]
    ],
    'users/get_users' => [
        'title' => 'Lấy danh sách cộng tác viên bởi use_id',
        'input' => [
            'id' => [
                'title' => 'Id của user cần lấy cộng tác viên',
                'rule' => 'required|integer'
            ],
            'sort' => [
                'title' => 'Cách sắp xếp (level|direct)',
                'rule' => ''
            ],
            'keyword' => [
                'title' => 'Từ khóa',
                'rule' => ''
            ]
        ]
    ],

    'users/add_device' => [
        'title' => 'Thêm mới 1 thiết bị',
        'input' => [
            'id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'registration_id' => [
                'title' => 'Mã thiết bị',
                'rule' => 'required'
            ],
            'info' => [
                'title' => 'Thông tin thiết bị',
                'rule' => ''
            ]
        ]
    ],

    'users/delete_device' => [
        'title' => 'Xóa thiết bị (Khi user logout)',
        'input' => [
            'id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ],
            'registration_id' => [
                'title' => 'Mã thiết bị',
                'rule' => 'required'
            ],
        ]
    ],

    'users/test' => [
        'title' => 'sinh ra để test',
        'input' => [
            'test' => [
                'title' => 'input test dau tien',
                'rule' => 'integer',
            ]
        ]
    ],

    'users/get_users_referral' => [
        'title' => 'Lấy toàn bộ users referral',
        'input' => [
            'referral_id' => [
                'title' => 'id referral của user hiện tại',
                'rule' => 'integer',
            ]
        ]
    ],

    'users/payment_request' => [
        'title' => 'Tạo yêu cầu rút tiền',
        'input' => [
            'user_id' => [
                'title' => 'User ID',
                'rule' => 'required|integer'
            ],
            'money' => [
                'title' => 'Số tiền muốn rút',
                'rule' => 'required|float|min_numeric,50000'
            ],
            'bank_id' => [
                'title' => 'ID tài khoản ngân hàng muốn nhận tiền',
                'rule' => 'required|integer'
            ]
        ]
    ],
    'users/find_by_username' => [
        'title' => 'Tìm user bằng username',
        'input' => [
            'username' => [
                'title' => 'Username',
                'rule' => 'required'
            ],
        ]
    ],
    'users/resend_confirm_code' => [
        'title' => 'Gửi lại mã xác thực',
        'input' => [
            'user_id' => [
                'title' => 'User id hoặc số điện thoại hoặc email',
                'rule' => 'required'
            ],
        ]
    ],

    'users/update_phone' => [
        'title' => 'Cập nhật số điện thoại',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required'
            ],
            'new_phone' => [
                'title' => 'Số điện thoại mới',
                'rule' => 'required|phone_number'
            ]
        ]
    ],

    'users/confirm_update_phone' => [
        'title' => 'Gửi lại mã xác thực',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required'
            ],
            'code' => [
                'title' => 'User id',
                'rule' => 'required'
            ],
        ]
    ],
    'users/change_ref_code' => [
        'title' => 'Thay đổi mã giới thiệu',
        'input' => [
            'user_id' => [
                'title' => 'User id',
                'rule' => 'required'
            ],
            'code' => [
                'title' => 'Mã giới thiệu',
                'rule' => 'required'
            ],
        ]
    ],
    'users/dangkythanhvien' => [
        'title' => 'Đăng ký thành viên',
        'input' => [
            'name' => [
                'title' => 'Tên',
                'rule' => 'required'
            ],
            'phone_number' => [
                'title' => 'Số điện thoại',
                'rule' => 'required'
            ],
            'email' => [
                'title' => 'Email',
                'rule' => 'required'
            ],
        ]
    ],
    'users/delete' => [
        'title' => 'Xóa user (Thay doi trang thai)',
        'input' => [
            'id' => [
                'title' => 'User id',
                'rule' => 'required|integer'
            ]
        ]
    ],

];
