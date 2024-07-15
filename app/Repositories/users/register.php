<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/24/19
 * Time: 23:32
 */
$vars = false;

//Đếm số user
/*$user_count = \App\Models\Users\Users::count();
if ($user_count > 0) {
    if(!empty(input('referral_code'))){
        if (input('referral_code') ?? false) {
            $referral_user = \App\Models\Users\Users::where('use_active != -99 && (use_login = \'' . input('referral_code') . '\' OR (use_id = \'' . input('referral_code') . '\' AND LENGTH(use_id) = LENGTH(\''. input('referral_code') .'\')) OR use_referer_code = \'' . input('referral_code') . '\')')
                ->find();
        }

        if (!($referral_user ?? false)) {
            throw new RuntimeException('Mã giới thiệu không hợp lệ', 400);
        }
    }
} else {
    if (input('referral_code') != 9999) {
        throw new RuntimeException('Mã giới thiệu không hợp lệ', 400);
    }
}
*/

/*if (input('password') != input('re_password')) {
    throw new RuntimeException('Mật khẩu xác thực không khớp', 400);
}*/


$email = '';
if (input('email')) {
    $email = strtolower(input('email'));
    /*if (filter_var(input('email'), FILTER_VALIDATE_EMAIL)) {
        $email = strtolower(input('email'));
        /*$check_user = \App\Models\Users\Users::where('use_email=\'' . $email . '\' AND use_active != -99')->find();
        if ($check_user) {
            if(!$check_user->active) {
                $vars = transformer_item($user, new \App\Transformers\UserTransformer());
                return [
                    'vars' => $vars
                ];
            }
            throw new RuntimeException('Email đã có người sử dụng', 400);
        }
    } else {
        throw new RuntimeException('Email không đúng định dạng', 400);
    }*/
}


// else {
$phone = strtolower(input('phone'));
$check_user = \App\Models\Users\Users::where('use_login=\'' . input('phone') . '\' AND use_active != -99')->find();
if ($check_user) {
    if(!$check_user->active) {
        $vars = transformer_item($user, new \App\Transformers\UserTransformer());
        return [
            'vars' => $vars
        ];
    }
    throw new RuntimeException('Số điện thoại đã có người sử dụng', 400);
}
// }

$confirm_code = rand(1000, 9999);

$user = [
    'use_active' => 0,
    'use_login' => input('phone'),
    'use_loginname' => input('phone'),
    'use_mobile' => $phone,
    'use_phone' => $phone,
    'use_password' => md5($phone), // mặc định passwork là sdt đăng ký
    'use_gender' => (int)input('gender'),
    'use_email' => $email,
    'use_name' => input('name'),
    'use_referral_id' => isset($referral_user) ? (int)$referral_user->id : 0,
    'use_register_confirm_code' => $confirm_code,
    'use_avatar' => 'a4cdd34e884618b1bbf8568cfb56e082.jpg',
    'use_province_id' => (int)input('province_id'),
    'use_district_id' => (int)input('district_id'),
    'use_ward_id' => (int)input('ward_id'),
    'use_address_register' => input('address_register'),
    'use_job_code' => (int)input('job_code'),
    'use_referer_code'=>'',
    'use_partner_note'=>'',
    'use_content'=>input('user_content')
];
$user_id = \App\Models\Users\Users::insert($user);

if (!$user_id) {
    throw  new RuntimeException('Đăng ký thành viên không thành công', 500);
}

$user = \App\Models\Users\Users::findByID($user_id);

$vars = transformer_item($user, new \App\Transformers\UserTransformer());

//Bắn notify cho user
//if (isset($referral_user)) {
//    \AppView\Helpers\Notification::to([$referral_user->id], 'Cộng tác viên đăng ký thành công', 'Có người vừa sử dụng mã giới thiệu của bạn để đăng ký tài khoản');
//}

//Tăng chỉ số cho người giới thiệu
//if (isset($referral_user)) {
//
//    $total_dirrect = \App\Models\Users\Users::where('use_referral_id', $referral_user->id)->count();
//
//    $referral_user->use_total_direct_refer = $total_dirrect;
//    $referral_user->use_total_refer++;
//    $referral_user->update();
//
//    while ($parent = $referral_user->parent) {
//        $parent->use_total_refer++;
//        $parent->update();
//        $referral_user = $referral_user->parent;
//    }
//}

if (isset($referral_user)) {
    $string = '.' . $referral_user->id . '.';
    while ($parent = $referral_user->parent) {
        $string .= $parent->id . '.';
        $referral_user = $referral_user->parent;
    }
    $user->all_level = $string;
    $user->update();
}

return [
    'vars' => $vars
];
