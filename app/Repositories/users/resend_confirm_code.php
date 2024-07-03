<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/17/19
 * Time: 21:02
 */

$vars = [];
$username = replaceMQ(input('user_id'));

$user = \App\Models\Users\Users::where('
use_active != -99 && (use_id = \'' . $username . '\' OR use_loginname = \'' . $username . '\' OR use_email = \'' . $username . '\' OR use_phone = \'' . $username . '\')
')->first();

if ($user) {
    //Gửi mã xác thực
    $new_code = rand(1000, 9999);
    $user->use_register_confirm_code = $new_code;
    $user->update();

    if ($user->phone) {

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
        $content = setting('template_sms_send_register_code', 'Ma xac thuc tai khoan cua ban la [code]');

        if ($is_mobi) {
//            $content = 'Ma check in cua ban la: [code]. Hotline [phone]';
            $content = 'Cam on Quy khach da su dung dich vu Cty_3Do. Ma xac thuc cua Quy khach la [code]. Chi tiet LH [phone] hoac email dodomogroup@gmail.com';
            $brandname = 'Cty_3Do';
//            $brandname = 'NhacLichhen';
        } else {
            $content = 'Cam on Quy khach da su dung dich vu Cty_3Do. Ma xac thuc cua Quy khach la [code]. Chi tiet LH [phone] hoac email dodomogroup@gmail.com';
            $brandname = 'Cty_3Do';
        }


        $content = str_replace('[code]', $new_code, $content);
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

//        echo $result;

//        $sms = new \AppView\Helpers\SpeedSMSAPI(config('app.speed_sms_access_token'));
//        $return = $sms->sendSMS([$user->phone], $content, \AppView\Helpers\SpeedSMSAPI::SMS_TYPE_CSKH, "");
    } elseif ($user->email) {
        //Gửi mail

        $title = 'Mã xác thực tài khoản dododo24h';
        $content = setting('template_email_send_register_code', 'Ma xac nhan cua ban la [code]');
        $content = str_replace('[code]', $new_code, $content);

        \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\SendEmailWorker::$name, \App\Workers\SendEmailWorker::class, [
            'email' => $user->email,
            'name' => $user->name,
            'title' => $title,
            'content' => $content,
        ]);
    }

    $vars = transformer_item($user, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars + ['result' => $result, 'content' => $content]
];