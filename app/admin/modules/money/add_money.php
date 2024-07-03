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

//dd($user);
// Lấy giá trị từ POST
//$type = getValue('wallet_type', 'int', 'POST', 0);
$type = 0;
//$request = null;
//if ($request_id = getValue('request_id')) {
//    $request = \App\Models\MoneyAddRequestNotify::findByID($request_id);
////    $money_add = (int)$request->money;
//}
//else {
$money_add = getValue('add_money', 'str', 'POST', '');

$money_add = str_replace('.', '', $money_add);
$money_add = str_replace(',', '', $money_add);
//}

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {


    //Check password and otp
    if (config('app.debug')) {

    } else {
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
    }

    if ($fs_errorMsg == '') {
        $exist = \App\Models\Users\UserWallet::where('usw_user_id', '=', $record_id)->first();
        if (!$exist) {

            $wallet_id = \App\Models\Users\UserWallet::insert([
                'usw_user_id' => $record_id,
                'usw_commission' => 0,
                'usw_charge' => 0
            ]);

            $arr_log = [
                'uwl_use_id' => $record_id,
                'uwl_wallet_id' => $wallet_id,
                'uwl_admin_id' => (int)$admin_id,
                'uwl_ip' => $ip_address,
                'uwl_money_old' => 0,
                'uwl_money_new' => (int)$money_add,
                'uwl_money_add' => (int)$money_add,
                'uwl_wallet_type' => $type,
                'uwl_type' => 0,
                'uwl_note' => 'Admin thêm tiền',
            ];
        } else {

            $money_old = $exist->usw_charge;
            $money_new = $exist->usw_charge + $money_add;

            $arr_log = [
                'uwl_use_id' => $record_id,
                'uwl_wallet_id' => $exist->id,
                'uwl_admin_id' => (int)$admin_id,
                'uwl_ip' => $ip_address,
                'uwl_money_old' => (int)$money_old,
                'uwl_money_new' => (int)$money_new,
                'uwl_money_add' => (int)$money_add,
                'uwl_wallet_type' => $type,
                'uwl_type' => 0,
                'uwl_note' => 'Admin thêm tiền',
            ];
        }

        $admin_note = getValue('admin_note', 'str', 'POST', '');
        if ($admin_note) {
            $arr_log['uwl_note'] = $admin_note;
        }

        $note = getValue('note', 'str', 'POST', '');
        $note = $note ? $note : 'Chúc mừng bạn đã được nạp thành công [money] từ ' . getValue('userlogin', 'str', 'SESSION', '') . 'lúc ' . date('d/m/Y H:i:s');
        $note = str_replace('[money]', number_format($money_add) . 'đ', $note);

//    \AppView\Helpers\Notification::to($record_id, 'Thay đổi số dư tài khoản', $note);

        add_money(
            $user->id, 
            (int)$money_add, 
            \App\Models\UserMoneyLog::TYPE_MONEY_ADD, 
            $note, 
            \App\Models\UserMoneyLog::SOURCE_ADMIN, 
            0, 
            $admin_id
        );

        admin_log($admin_id, ADMIN_LOG_ACTION_ADD, $user->id, 'Cộng ' . number_format($money_add) . ' vào tài khoản (' . $user->id . ')' . $user->name);

        $wallet_log = \App\Models\Users\UserWalletLog::insert($arr_log);

        if ($request_id = getValue('request_id')) {
            \App\Models\MoneyAddRequestNotify::where('marn_id', $request_id)->update([
                'marn_admin_id' => (int)$admin_id,
                'marn_status' => 1,
                'marn_money_add' => (int)$money_add
            ]);
            echo '
<script type="text/javascript">
window.parent.location.reload();
</script>                
                ';
            die;
        } else {
            \AppView\Helpers\Facades\FlashMessage::success('Nạp tiền thành công', url_back());
        }
    }

}

echo $blade->view()->make('add_money', get_defined_vars())->render();
return;
?>