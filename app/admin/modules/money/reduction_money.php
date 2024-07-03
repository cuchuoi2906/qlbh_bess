<?

require_once 'inc_security.php';

checkAddEdit("add");

//$userlogin = getValue("userlogin", "str", "SESSION", "", 1);

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

$fs_action = getURL();
$fs_errorMsg = "";

$ip_address = get_user_ip();
$admin_id = getValue("user_id", "int", "SESSION", "", 1);

$user = \App\Models\Users\Users::with(['wallet'])
    ->where('use_id', '=', $record_id)
    ->find();

if (!($user ?? false)) {
    die('User không tồn tại');
}

$type = 0;

$money_reduction = getValue('reduction_money', 'str', 'POST', '');

$money_reduction = str_replace('.', '', $money_reduction);
$money_reduction = str_replace(',', '', $money_reduction);

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {

    //Check password and otp
    $password = getValue('admin_pass', 'str', 'POST', '');
    if (md5($password) != getValue('password', 'str', 'SESSION', '')) {
        $fs_errorMsg .= 'Mật khẩu không đúng <br/>';
    }
    $otp = getValue('otp', 'int', 'POST', '');
    if (
        $otp != getValue('money_otp', 'int', 'SESSION', '')
        || time() > getValue('money_expires', 'int', 'SESSION', 0)
    ) {
        $fs_errorMsg .= 'OTP không đúng <br/>';
    }

    if (($user->wallet->charge ?? 0) < $money_reduction) {
        $fs_errorMsg .= 'Bạn không được phép điều chỉnh giảm lớn hơn số dư hiện tại của ví tiêu dùng. Số dư ví tiêu dùng của user là ' . number_format(($user->wallet->charge ?? 0)) . 'đ. <br/>';
    }

    if ($fs_errorMsg == '') {
        $exist = \App\Models\Users\UserWallet::where('usw_user_id', '=', $record_id)->first();

        if ($exist) {
            //tài khoản tạm giữ
            $money_old = $exist->usw_charge;
            $money_new = $exist->usw_charge - $money_reduction;

            $arr_log = [
                'uwl_use_id' => $record_id,
                'uwl_wallet_id' => $exist->id,
                'uwl_admin_id' => (int)$admin_id,
                'uwl_ip' => $ip_address,
                'uwl_money_old' => $money_old,
                'uwl_money_new' => $money_new,
                'uwl_money_reduction' => $money_reduction,
                'uwl_wallet_type' => $type,
                'uwl_type' => 0,
                'uwl_note' => 'Admin trừ tiền',
            ];

            $admin_note = getValue('admin_note', 'str', 'POST', '');
            if ($admin_note) {
                $arr_log['uwl_note'] = $admin_note;
            }

            $note = getValue('note', 'str', 'POST', '');
            $note = $note ? $note : 'Tài khoản của bạn vừa bị giảm trừ [money] từ ' . getValue('userlogin', 'str', 'SESSION', '') . 'lúc ' . date('d/m/Y H:i:s');
            $note = str_replace('[money]', number_format($money_reduction) . 'đ', $note);

//        \AppView\Helpers\Notification::to($record_id, 'Thay đổi số dư tài khoản', $note);

            sub_money($user->id, $money_reduction, \App\Models\UserMoneyLog::TYPE_MONEY_ADD, $note, \App\Models\UserMoneyLog::SOURCE_ADMIN);

            admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $user->id, 'Trừ ' . number_format($money_reduction) . ' từ tài khoản (' . $user->id . ')' . $user->name);

            $wallet_log = \App\Models\Users\UserWalletLog::insert($arr_log);

            if ($request_id = getValue('request_id')) {
                \App\Models\MoneyAddRequestNotify::where('marn_id', $request_id)->update([
                    'marn_admin_id' => (int)$admin_id,
                    'marn_status' => 1
                ]);
                echo '
<script type="text/javascript">
window.parent.location.reload();
</script>                
                ';
            } else {
                \AppView\Helpers\Facades\FlashMessage::success('Trừ tiền thành công', url_back());
            }

        }
    }

}

echo $blade->view()->make('reduction_money', get_defined_vars())->render();
return;
?>