<?php

require_once 'inc_security.php';

checkAddEdit("add");

//$userlogin = getValue("userlogin", "str", "SESSION", "", 1);

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$money_batch_id = getValue("money_batch_id","str");
if(empty($money_batch_id)){
    die('User không tồn tại');
}
$fs_action = getURL();
$fs_errorMsg = "";

$ip_address = get_user_ip();
$admin_id = getValue("user_id", "int", "SESSION", "", 1);
    
$arr_money_batch_id = explode(',',$money_batch_id);
$v_arr_name_user = [];
for($i=0;$i<count($arr_money_batch_id);$i++){
    $db_data = new db_query("SELECT *
            from money_load_batch where money_id= ".$arr_money_batch_id[$i]."
                AND money_status =1
             LIMIT 1");
    $row = $db_data->fetch();
    $user = \App\Models\Users\Users::with(['wallet'])
        ->where('use_id', '=', $row[0]['money_user_id'])
        ->find();
    if($user){
        $v_arr_name_user['c_id'][] =$user->id;
        $v_arr_name_user['c_name'][] =$user->name;
    }
}
if (!check_array($v_arr_name_user)) {
    die('User không tồn tại');
}


$type = 0;
$money_add = getValue('add_money', 'str', 'POST', '');

$money_add = str_replace('.', '', $money_add);
$money_add = str_replace(',', '', $money_add);

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
    $add_money_batch_id = getValue('add_money_batch_id', 'str', 'POST', '');
    $arr_add_money_batch_id = explode(',',$add_money_batch_id);
    $v_arr_name_user_update = [];
    $h=0;
    for($i=0;$i<count($arr_add_money_batch_id);$i++){
        $db_data = new db_query("SELECT *
            from money_load_batch where money_id= ".$arr_add_money_batch_id[$i]."
                AND money_status =1
             LIMIT 1");
        $row = $db_data->fetch();
        $user = \App\Models\Users\Users::with(['wallet'])
            ->where('use_id', '=', $row[0]['money_user_id'])
            ->find();
        if($user){
            $v_arr_name_user_update[$h]['c_id'] =$user->id;
            $v_arr_name_user_update[$h]['c_name'] =$user->name;
            $v_arr_name_user_update[$h]['money_charge'] =$row[0]['money_charge'];
            $v_arr_name_user_update[$h]['money_id'] =$row[0]['money_id'];
            $h++;
        }
    }
    if(!check_array($v_arr_name_user_update)){
        $fs_errorMsg .= 'User không tồn tại <br/>';
    }
    if ($fs_errorMsg == '') {
        $logArr = [];
        for($j=0;$j<count($v_arr_name_user_update);$j++){
            $record_id = intval($v_arr_name_user_update[$j]['c_id']);
            $money_add = $v_arr_name_user_update[$j]['money_charge'];
            $money_id = $v_arr_name_user_update[$j]['money_id'];
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
            $logArr[] = 'Cộng ' . number_format($money_add) . ' vào tài khoản (' . $user->id . ')';
            $wallet_log = \App\Models\Users\UserWalletLog::insert($arr_log);
            
            $pri_update_at = gmdate("Y-m-d H:i:s", time());
            \App\Models\Moneyloadbatch::where('money_id', $money_id)->update([
                'money_admin_id' => (int)$admin_id,
                'money_status' => 2,
                'money_updated_at'=>$pri_update_at
            ]);
        }
        echo '
            <script type="text/javascript">
            alert("Cộng tiền thành công");
            var timest = new Date().valueOf();
            var url = "../money_load_batch/import.php?"+timest;
            window.parent.location.href=url;
            </script>                
            ';
        die;
    }

}

echo $blade->view()->make('add_money_batch', get_defined_vars())->render();
return;
?>