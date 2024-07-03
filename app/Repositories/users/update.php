<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 12:06
 * 'id' => [
 * 'title' => 'ID',
 * 'rule' => 'required|integer'
 * ],
 * 'name' => [
 * 'title' => 'Tên',
 * 'rule' => ''
 * ],
 * 'avatar' => [
 * 'title' => 'Ảnh đại diện. Chỉ bắn tên ảnh. Ảnh lưu trong thư mục upload/images',
 * 'rule' => ''
 * ],
 * 'gender' => [
 * 'title' => 'Giới tính. 0 = female, 1 = male',
 * 'rule' => 'integer'
 * ],
 * 'birthdays' => [
 * 'title' => 'Ngày sinh. bắn string lên dạng dd/mm/yyyy',
 * 'rule' => ''
 * ],
 * 'address' => [
 * 'title' => 'Địa chỉ',
 * 'rule' => ''
 * ],
 * 'identity_number' => [
 * 'title' => 'Số chứng mình nhân dân',
 * 'rule' => ''
 * ],
 * 'identity_front_image' => [
 * 'title' => 'Ảnh chứng minh mặt trước. Chỉ bắn tên ảnh. Lưu lại upload/images',
 * 'rule' => ''
 * ],
 * 'identity_back_image' => [
 * 'title' => 'Ảnh chứng minh mặt sau. Chỉ bắn tên ảnh. Lưu lại upload/images',
 * 'rule' => ''
 * ],
 * 'password' => [
 * 'title' => 'Password',
 * 'rule' => ''
 * ]
 */

//Check user
$affected = false;
$user = \App\Models\Users\Users::findByID((int)input('id'));
$vars = [];
if ($user) {

    if (input('phone') ?? false) {
        $check_phone = \App\Models\Users\Users::where('use_phone', input('phone'))->first();
        if ($check_phone) {
            throw new Exception('Số điện thoại đã có người sử dụng', 400);
        } else {
            $user->phone = input('phone');
            $user->mobile = input('phone');
        }
    }

    if (input('password') && $user->password != md5(input('old_password'))) {
        throw new RuntimeException('Mật khẩu cũ không đúng', 400);
    }

    if (input('password') && input('password') == input('old_password')) {
        throw new RuntimeException('Mật khẩu mới không được trùng với mật khẩu cũ', 400);
    }

    if (input('password')) {
        $user->use_password = md5(input('password'));
    } else {
        if (!input('name')) {
            throw new RuntimeException('Bạn phải hoàn thiện thông tin Họ tên', 400);
        }
        if (input('referer_code')) {
            if (!preg_match('/[^A-Za-z0-9]/', input('referer_code'))) // '/[^a-z\d]/i' should also work.
            {
                throw new RuntimeException('Mã giới thiệu chỉ được bao gồm chữ và số', 400);
            }

            $check = \App\Models\Users\Users::where('use_id', '<>', $user->id)
                ->where('use_referer_code', '=', input('referer_code'))
                ->first();
            if ($check) {
                throw new RuntimeException('Mã giới thiệu đã có người sử dụng', 419);
            }

            $user->referer_code = input('referer_code');
        }
        $user->use_name = input('name');
        $user->use_email = input('email');
        $user->use_avatar = input('avatar') ? input('avatar') : $user->use_avatar;
        $user->use_gender = (int)input('gender');
        $user->use_birthdays = input('birthdays');
        $user->use_address = input('address');
        $user->use_identity_number = input('identity_number');
        $user->use_identity_front_image = input('identity_front_image');
        $user->use_identity_back_image = input('identity_back_image');
    }


    $affected = $user->update();
}

if ($affected) {
    $user = \App\Models\Users\Users::findByID((int)input('id'));
    $vars = transformer_item($user, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars
];


