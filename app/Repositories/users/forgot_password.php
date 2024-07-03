<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/1/19
 * Time: 23:20
 */

$vars = false;

if (filter_var(input('phone'), FILTER_VALIDATE_EMAIL)) {
    $user = \App\Models\Users\Users::where('use_email', input('phone'))->first();
} else {
    $user = \App\Models\Users\Users::where('use_login', input('phone'))->first();
}

if (!$user || $user->active == 0) {
    throw new RuntimeException('Số điện thoại/Email không tồn tại hoặc đã hủy. Vui lòng kiểm tra lại.', 400);
}

$random_code = rand(1111, 9999);
$user->use_forgot_password_confirm_code = $random_code;
if ($user->update()) {
    $vars = $random_code;

    if ($user->phone) {
        //Gửi SMS
        $content = setting('template_sms_send_forgot_password_code', 'Ma xac thuc tai khoan cua ban la [code]');

        //Check đầu số mobifone
        //090 – 093 – 089 – 070 – 079 – 077- 076 – 078
        $mobi = ['090', '093', '089', '070', '079', '077', '076', '078'];
        $is_mobi = false;
        foreach ($mobi as $number) {
            if (0 === strpos($user->phone, $number)) {
                $is_mobi = true;
            }
        }

        //Gửi SMS
        if ($is_mobi) {
//            $content = 'Ma check in cua ban la: [code]. Hotline [phone]';
            $content = 'Cam on Quy khach da su dung dich vu Cty_3Do. Ma xac thuc cua Quy khach la [code]. Chi tiet LH [phone] hoac email dodomogroup@gmail.com';
            $brandname = 'Cty_3Do';
//            $brandname = 'NhacLichhen';
        } else {
            $content = 'Cam on Quy khach da su dung dich vu Cty_3Do. Ma xac thuc cua Quy khach la [code]. Chi tiet LH [phone] hoac email dodomogroup@gmail.com';
            $brandname = 'Cty_3Do';
        }

        $content = str_replace('[code]', $random_code, $content);
        $content = str_replace('[phone]', '0246753966', $content);

        //Api Key:0795B6BC827E698A7793F57E679D4C
        //Secret Key:A29F75C2592DF18353B5FAC1A8F134
        $param = array(
            'ApiKey' => '3EA3A9B4AC92ECD9757C1464CF5ED2',
            'Content' => $content,
            'Phone' => $user->phone,
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


    } elseif ($user->email) {
        //Gửi mail

        $title = 'Lấy lại mật khẩu dododo24h';
        $content = setting('template_email_send_forgot_password_code', 'Ma xac thuc tai khoan cua ban la [code]');
        $content = str_replace('[code]', $random_code, $content);

        \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\SendEmailWorker::$name, \App\Workers\SendEmailWorker::class, [
            'email' => $user->email,
            'name' => $user->name,
            'title' => $title,
            'content' => $content,
        ]);
    }


}

return [
    'vars' => $vars
];