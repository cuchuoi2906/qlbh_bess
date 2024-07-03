<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("add");

//Khai báo biến khi thêm mới
$fs_title = "";
$fs_action = getURL();
$fs_errorMsg = "";
$myform = new generate_form();

$use_active = getValue('use_active', 'int', 'POST', 1);
$myform->add('use_active', 'use_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);

$use_phone = getValue('use_phone', 'str', 'POST', '');
if(filter_var($use_phone, FILTER_VALIDATE_EMAIL))
{
    $myform->add('use_email', 'use_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_phone, 1, 'Bạn chưa nhập email / số điện thoại', 1, 'Email đã có người sử dụng');
}else{
    $myform->add('use_phone', 'use_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_phone, 1, 'Bạn chưa nhập email / số điện thoại', 1, 'Số điện thoại đã có người sử dụng');
}

$myform->add('use_loginname', 'use_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_phone, 1, 'Bạn chưa nhập số điện thoại');
$myform->add('use_login', 'use_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_phone, 1, 'Bạn chưa nhập số điện thoại');
$myform->add('use_mobile', 'use_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_phone, 1, 'Bạn chưa nhập số điện thoại');

$use_name = getValue('use_name', 'str', 'POST', '');
$myform->add('use_name', 'use_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_name, 1, 'Bạn chưa nhập tên người dùng');

$use_password = getValue('use_password', 'str', 'POST', '');

$use_password_retype = getValue('use_password_retype', 'str', 'POST', '');
if ($use_password_retype != $use_password) {
    $fs_errorMsg .= 'Mật khẩu nhắc lại không chính xác <br/>';
}

$myform->add('use_password', 'use_password', FORM_ADD_TYPE_HASH_PASSWORD, FORM_ADD_VALUE_FROM_GLOBAL, $use_password, 1, 'Bạn chưa nhập mật khẩu');

$use_gender = getValue('use_gender', 'int', 'POST', 0);
$myform->add('use_gender', 'use_gender', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_gender);

$use_premium = getValue('use_premium', 'int', 'POST', 1);
$myform->add('use_premium', 'use_premium', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_premium);

$use_premium_commission = getValue('use_premium_commission', 'int', 'POST', 0);
$myform->add('use_premium_commission', 'use_premium_commission', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_premium_commission);

$use_referral_id = getValue('use_referral_id', 'int', 'POST', 0);
$myform->add('use_referral_id', 'use_referral_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_referral_id);

$use_content = getValue('use_content', 'str', 'POST', '');
$myform->add('use_content', 'use_content', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_content);

$use_leader = getValue('use_sale', 'int', 'POST', 0);
$myform->add('use_sale', 'use_sale', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_sale);

$user_sale_id = getValue('user_sale_id', 'int', 'POST', 0);
$myform->add('user_sale_id', 'user_sale_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $user_sale_id);

$use_province_id = getValue('use_province_id', 'int', 'POST', 0);
$use_district_id = getValue('use_district_id', 'int', 'POST', 0);
$use_ward_id = getValue('use_ward_id', 'int', 'POST', 0);
$use_address_register = getValue('use_address_register', 'str', 'POST', '');
if(!$use_province_id || !$use_district_id || !$use_ward_id || empty($use_address_register)){
    //$fs_errorMsg .= 'Bạn phải nhập địa chỉ <br/>';
}
$use_job_code = getValue('use_job_code', 'int', 'POST', '');
if(!$use_job_code){
    //$fs_errorMsg .= 'Bạn phải chọn nghề nghiệp <br/>';
}
$use_type = getValue('use_type', 'int', 'POST', 0);

$myform->add('use_province_id', 'use_province_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_province_id);
$myform->add('use_district_id', 'use_district_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_district_id);
$myform->add('use_ward_id', 'use_ward_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_ward_id);
$myform->add('use_address_register', 'use_address_register', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_address_register);
$myform->add('use_job_code', 'use_job_code', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_job_code);
$myform->add('use_type', 'use_type', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_type);


$myform->addTable($fs_table);

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {

    if ($fs_errorMsg == "") {

//        //Check phone
//        $phone = getValue('use_phone', 'str', 'POST', '');
//        $phone = trim($phone);
//        $check = App\Models\Users\Users::where('use_phone', '=', $phone)
//            ->first();
//        if($check)
//        {
//            $fs_errorMsg .= 'Số điện thoại đã có người sử dụng' . PHP_EOL;
//        }

        $fs_errorMsg .= $myform->checkdata();

        if ($fs_errorMsg == '') {

            //echo $myform->generate_insert_SQL();
            $sql = $myform->generate_insert_SQL();
            // _debug($sqlUpdate);die;
            $db_excute = new db_execute_return();
            $id = $db_excute->db_execute($sql);
            unset($db_excute);

            if ($id) {
                if($use_referral_id > 0){
                    $user = \App\Models\Users\Users::findByID($id);
                    $userReferral = \App\Models\Users\Users::findByID($use_referral_id);
                    $referral_user = \App\Models\Users\Users::where('use_login = \'' . $userReferral->use_login . '\' OR (use_id = \'' . $userReferral->use_login . '\' AND LENGTH(use_id) = LENGTH(\''. $userReferral->use_login .'\')) OR use_referer_code = \'' . $userReferral->use_login . '\'')
                    ->find();
                    if (isset($referral_user)) {
                        $string = '.' . $referral_user->id . '.';
                        while ($parent = $referral_user->parent) {
                            $string .= $parent->id . '.';
                            $referral_user = $referral_user->parent;
                        }
                        $user->all_level = $string;
                        $user->update();
                    }
                }
                \AppView\Helpers\Facades\FlashMessage::success('Tạo người dùng thành công', url_back());
            }

        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")
$sale_user = [];
$sale_user_model = \App\Models\Users\Users::where('use_sale', 1)->all();
foreach ($sale_user_model as $items) {
    $sale_user[$items->id] = $items->id.' - '.$items->name . ' - ' . $items->phone;
}
$sale_user[0] = 'Chọn sale phụ trách';

$users = \App\Models\Users\Users::where('use_active', 1)->where('use_type', '!=', 1)->all();
$users_info = $users;
$users = $users->lists('use_id', 'use_name');
$users = [0 => 'Chọn cấp trên'] + $users;

$province_obj = \App\Models\Province::all();
$province = $province_obj->lists('prov_id', 'prov_name');

echo $blade->view()->make('add', get_defined_vars())->render();
return;
?>