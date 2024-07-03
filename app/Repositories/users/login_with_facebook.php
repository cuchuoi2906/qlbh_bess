<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 9/5/19
 * Time: 14:19
 */

$vars = false;

$fb = new \AppView\Helpers\FacebookHelpers();

$user_info = $fb->getInfo(input('token'));

if ($user_info['email'] ?? false) {
    //Check user
    $user_check = App\Models\Users\Users::where('use_email', $user_info['email'])->find();
    if (!$user_check) {
        //Tạo user
        //get avatar
        $avatar = md5(uniqid()) . '.jpg';
        file_put_contents(ROOT . '/public/upload/images/' . $avatar, file_get_contents($user_info['image']));

        $confirm_code = rand(1000, 9999);

        $id = \App\Models\Users\Users::insert([
            'use_name' => $user_info['name'],
            'use_gender' => (($user_info['gender'] ?? 'male') == 'male') ? 1 : 0,
            'use_email' => $user_info['email'],
            'use_login' => $user_info['email'],
            'use_loginname' => $user_info['email'],
            'use_birthdays' => $user_info['birthday'] ?? '',
            'use_avatar' => $avatar,
            'use_password' => md5(rand(10000000, 99999999)),
            'use_register_confirm_code' => $confirm_code,
        ]);

        $user_check = \App\Models\Users\Users::findByID($id);
    }

    //Đăng nhập
    if ($user_check) {
        $myUser = new \user();

        $_COOKIE["loginname"] = $user_check['use_email'];
        $_COOKIE["PHPSESSlD"] = $user_check['use_password'];

        $myUser = new \user();

        $myUser->logged = 1;
        $myUser->savecookie(2 * 86400);

        $vars = transformer_item($user_check, new \App\Transformers\UserTransformer(), ['parent']);
    }
}

return [
    'vars' => $vars
];