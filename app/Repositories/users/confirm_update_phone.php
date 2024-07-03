<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/9/20
 * Time: 11:57
 */

$vars = [
    'success' => 0
];

$user_id = input('user_id');
$code = input('code');
$user = \App\Models\Users\Users::findByID($user_id);
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

$change_phone = $user->changePhone;
if (!$change_phone) {
    throw  new RuntimeException('Không tồn tại yêu cầu đổi số điện thoại', 404);
}

//Xác nhận số điện thoại cũ
if (!$change_phone->can_confirm_new_phone) {
    if ($code != $change_phone->old_code) {
        throw new RuntimeException('Mã xác thực không đúng', 400);
    }

    //Đúng thì update
    $change_phone->can_confirm_new_phone = 1;
    $new_code = rand(1000, 9999);
    $change_phone->new_code = $new_code;
    $change_phone->update();

    //Gửi mã xác thực cho số điện thoại mới
    $content = 'Cam on Quy khach da su dung dich vu Cty_3Do. Ma xac thuc cua Quy khach la [code]. Chi tiet LH [phone] hoac email dodomogroup@gmail.com';
    $brandname = 'Cty_3Do';
    $content = str_replace('[code]', $new_code, $content);
    $content = str_replace('[phone]', '0246753966', $content);
    $param = array(
        'ApiKey' => '3EA3A9B4AC92ECD9757C1464CF5ED2',
        'Content' => $content,
        'Phone' => $change_phone->new_phone,
        'SecretKey' => '5AE842F152366FF49AB1EA149FCE3B',
        'SmsType' => 2,
        'Brandname' => $brandname,
    );
    $data_string = json_encode($param);

    // URL có chứa hai thông tin name và diachi
    $url = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_post_json/';
    // Khởi tạo CURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    curl_close($ch);

    $vars = [
        'confirm_next_step' => 1
    ];

} else {
    if ($code != $change_phone->new_code) {
        throw new RuntimeException('Mã xác thực không đúng', 400);
    }

    $check_phone = \App\Models\Users\Users::where('use_phone', $change_phone->new_phone)->find();
    if ($check_phone) {
        throw new RuntimeException('Số điện thoại ' . $change_phone->new_phone . ' đã tồn tại trên hệ thống', 400);
    }

    //Update số điện thoại mới
    if ($user->loginname == $user->phone) {
        $user->loginname = $change_phone->new_phone;
        $user->login = $change_phone->new_phone;
    }
    $user->phone = $change_phone->new_phone;
    $check = $user->update();
    if ($check) {
        $change_phone->delete();
        $vars = [
            'success' => 1,
            'confirm_next_step' => 0
        ];
    }
}

return [
    'vars' => $vars
];