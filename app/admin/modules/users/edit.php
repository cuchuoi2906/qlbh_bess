<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

$user = \App\Models\Users\Users::findByID($record_id);

//Khai báo biến khi thêm mới
$fs_title = "";
$fs_action = getURL();
$fs_errorMsg = "";
$myform = new generate_form();

$use_active = getValue('use_active', 'int', 'POST', 1);
$myform->add('use_active', 'use_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);

$use_name = getValue('use_name', 'str', 'POST', '');
$myform->add('use_name', 'use_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_name, 1, 'Bạn chưa nhập tên người dùng');

$use_address = getValue('use_address', 'str', 'POST', '');
$myform->add('use_address', 'use_address', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

$use_gender = getValue('use_gender', 'int', 'POST', 0);
$myform->add('use_gender', 'use_gender', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_gender);

$use_level = getValue('use_level', 'int', 'POST', 1);
$myform->add('use_level', 'use_level', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_level);

$use_premium = getValue('use_premium', 'int', 'POST', 1);
$myform->add('use_premium', 'use_premium', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_premium);

$use_premium_commission = getValue('use_premium_commission', 'int', 'POST', 1);
$myform->add('use_premium_commission', 'use_premium_commission', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_premium_commission);


$use_password = getValue('use_password', 'str', 'POST', '');
if ($use_password) {
    $use_password_retype = getValue('use_password_retype', 'str', 'POST', '');
    if ($use_password_retype != $use_password) {
        $fs_errorMsg .= 'Mật khẩu nhắc lại không chính xác <br/>';
    }
    $myform->add('use_password', 'use_password', FORM_ADD_TYPE_HASH_PASSWORD, FORM_ADD_VALUE_FROM_GLOBAL, $use_password, 1, 'Bạn chưa nhập mật khẩu');
}

$use_content = getValue('use_content', 'str', 'POST', '');
$myform->add('use_content', 'use_content', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_content);

$use_sale = getValue('use_sale', 'int', 'POST', 0);
$myform->add('use_sale', 'use_sale', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_sale);

$use_source = getValue('use_source', 'int', 'POST', 0);
$myform->add('use_source', 'use_source', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_source);

$user_sale_id = getValue('user_sale_id', 'int', 'POST', 0);
$myform->add('user_sale_id', 'user_sale_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $user_sale_id);

$use_type = getValue('use_type', 'int', 'POST', 0);
$myform->add('use_type', 'use_type', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $use_type);

$myform->addTable($fs_table);

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {

    if ($user->level > $use_level) {
        $fs_errorMsg .= 'Bạn phải chọn level lớn hơn level hiện tại <br/>';
    }

    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();
        $upload = new upload('use_avatar', $fs_filepath, $fs_extension, $fs_filesize);

        if ($upload->common_error == '') {
            $file_name = $upload->file_name;
            $myform->add('use_avatar', 'file_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
        }
        unset($upload);
        if ($fs_errorMsg == '') {
            //echo $myform->generate_insert_SQL();
            $sqlUpdate = $myform->generate_update_SQL('use_id', $record_id);
            // _debug($sqlUpdate);die;
            $db_excute = new db_execute($sqlUpdate);
            unset($db_excute);

            \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\CountChildUserWorker::$name, \App\Workers\CountChildUserWorker::class, [
                'id' => $user->id
            ]);

            \AppView\Helpers\Facades\FlashMessage::success('Cập nhật thông tin thành công', url_back());
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")
$sale_user = [];
$sale_user_model = \App\Models\Users\Users::where('use_sale', 1)->all();
foreach ($sale_user_model as $items) {
    $sale_user[$items->id] = $items->id.' - '.$items->name . ' - ' . $items->phone;
}
$sale_user[0] = 'Chọn sale phụ trách';

//lay du lieu cua record can sua doi
$db_data = new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
if ($row = $db_data->fetch(true)) {
    foreach ($row as $key => $value) {
        if ($key != 'lang_id' && $key != 'admin_id') $$key = $value;
    }
} else {
    exit();
}
unset($db_data);

echo $blade->view()->make('edit', get_defined_vars())->render();
return;
?>