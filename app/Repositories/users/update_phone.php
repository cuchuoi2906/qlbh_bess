<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/9/20
 * Time: 10:22
 */

$vars = true;

$user = \App\Models\Users\Users::findByID(input('user_id'));
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

//Clear phone number
$phone = input('new_phone');
$phone = str_replace('+84', '0', $phone);
$phone = str_replace(' ', '', $phone);
$phone = trim($phone);

//Check phone
$check = \App\Models\Users\Users::withTrash()->where('use_phone', $phone)->first();
if ($check) {
    throw new RuntimeException('Số điện thoại đã tồn tại trên hệ thống', 400);
}

\App\Models\Users\UserChangePhone::where('ucp_user_id', (int)$user->id)->delete();

$new_code = rand(1000, 9999);
$data = [
    'ucp_user_id' => (int)$user->id,
    'ucp_new_phone' => $phone,
    'ucp_new_code' => $new_code,
    'ucp_can_confirm_new_phone' => 1
];
if ($user->phone) {
    $old_code = rand(1000, 9999);
    $data['ucp_old_phone'] = $user->phone;
    $data['ucp_old_code'] = $old_code;
    $data['ucp_can_confirm_new_phone'] = 0;
}

$vars = \App\Models\Users\UserChangePhone::insert($data);

if ($vars) {
    $content = 'Cam on Quy khach da su dung dich vu Cty_3Do. Ma xac thuc cua Quy khach la [code]. Chi tiet LH [phone] hoac email dodomogroup@gmail.com';
    $brandname = 'Cty_3Do';
    $content = str_replace('[code]', $data['ucp_can_confirm_new_phone'] ? $new_code : $old_code, $content);
    $content = str_replace('[phone]', '0246753966', $content);
    $param = array(
        'ApiKey' => '3EA3A9B4AC92ECD9757C1464CF5ED2',
        'Content' => $content,
        'Phone' => $data['ucp_can_confirm_new_phone'] ? $phone : $user->phone,
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
}

return [
    'vars' => [
        'confirm_step' => $data['ucp_can_confirm_new_phone'] ? 1 : 2
    ]
];