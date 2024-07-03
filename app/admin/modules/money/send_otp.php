<?

require_once 'inc_security.php';
disable_debug_bar();
checkAddEdit("add");

$admin = \App\Models\AdminUser::findByID($admin_id);

$user = \App\Models\Users\Users::findByID(getValue('user_id', 'int', 'POST'));

$otp = rand(1000, 9999);

$_SESSION['money_otp'] = $otp;
$_SESSION['money_expires'] = time() + 30;

$type = getValue('type', 'int', 'POST', 1);
$type = $type == 1 ? $type : 2;
try {
    if ($type == 1) {
        $content = 'Bạn đang thực hiện thay đổi số dư ví tiêu dùng cho user ' . $user->name . PHP_EOL;
        $content .= 'Mã bảo mật của bạn là [otp]' . PHP_EOL;
        $content .= 'Mã bảo mật sẽ có hiệu lục trong vòng 30s';

        $content = setting('email_template_send_otp_money', $content);
        $content = str_replace('[otp]', $otp, $content);
        //echo  $admin->email;

        \AppView\Helpers\SendGridEmailHelpers::send('Mã bảo mật của bạn', $admin->email, $admin->name, $content);
        $_SESSION['otp_type'] = 'email';
    } else {
        $content = 'Ban dang thuc hien thay doi so du vi tieu dung cho user ' . removeAccent($user->name) . '. Ma bao mat cua ban la ' . $otp . '. Ma bao mat se co hieu luc trong vong 30s.';
        $sms = new \AppView\Helpers\TwoFactorAPI(config('app.speed_sms_access_token'));
        $return = $sms->pinCreate($admin->phone, $content, 'zzg8q4CBOgwZSXf08dLvGOV8E4FyW_H5');
        $_SESSION['otp_type'] = 'sms';
    }
    echo json_encode([
        'error' => 0,
        'otp' => $_SESSION['money_otp']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => 1,
        'message' => $e->getMessage()
    ]);
}
die;

