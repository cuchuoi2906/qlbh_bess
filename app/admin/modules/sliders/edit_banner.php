<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin banner";
$fs_action = getURL();
$fs_errorMsg = "";


// Lấy giá trị từ POST
$ban_title = getValue('ban_title', 'str', 'POST', '');
$ban_link = getValue('ban_link', 'str', 'POST', '');
$ban_type = getValue('ban_type', 'int', 'POST', 0);
$ban_html = getValue('ban_html', 'str', 'POST', '');
$ban_active = getValue('ban_active', 'int', 'POST', 1);
$ban_update_time = time();

$ban_object_type = getValue('ban_object_type', 'str', 'POST', '');
$ban_object_id = getValue('ban_object_id', 'int', 'POST', 0);

//Call Class generate_form();
$myform = new generate_form();
$myform->add('ban_title', 'ban_title', 0, 1, '', 1, 'Chưa nhập tiêu đề banner');
$myform->add('ban_link', 'ban_link', 0, 1, '', 0, 'Chưa nhập đường dẫn');
$myform->add('ban_html', 'ban_html', 0, 1, 0);
$myform->add('ban_type', 'ban_type', 1, 1, 0);
$myform->add('ban_active', 'ban_active', 1, 1, 0);
$myform->add('ban_update_time', 'ban_update_time', 1, 1, 0);

$myform->add('ban_object_type', 'ban_object_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $ban_object_type);
$myform->add('ban_object_id', 'ban_object_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $ban_object_id);

$myform->addTable('banner');

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();
        $upload = new upload('ban_image', $fs_filepath, $fs_extension, $fs_filesize);
        if ($upload->common_error == '') {
            $file_name = $upload->file_name;
            if ($file_name != '') {
                $myform->add('ban_image', 'file_name', 0, 1, '', 1, '');
            }
        }
        if ($fs_errorMsg == '') {
            //echo $myform->generate_insert_SQL();
            $sqlUpdate = $myform->generate_update_SQL('ban_id', $record_id);
            // _debug($sqlUpdate);die;
            $db_excute = new db_execute($sqlUpdate);
            unset($db_excute);
            echo 'Sửa thành công!';
            redirect($fs_redirect);
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

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

echo $blade->view()->make('edit_banner', get_defined_vars())->render();
return;
?>