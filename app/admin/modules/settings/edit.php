<?
require_once 'inc_security.php';

checkAddEdit("edit");
//dd(getValue('page'));
$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php"))) . '?&page=' . getValue('page');
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin key";
$fs_action = getURL();
$fs_errorMsg = "";

// Lấy giá trị từ POST
$swe_label = getValue('swe_label', 'str', 'POST', '');
$swe_key = getValue('swe_key', 'str', 'POST', '');
$swe_type = getValue('swe_type', 'str', 'POST', '');
$swe_update_time = time();
$swe_value = '';
//Call Class generate_form();
$myform = new generate_form();

if ($swe_type == 'image'):
    $upload = new upload('swe_value_image', $fs_filepath, $fs_extension, $fs_filesize);
endif;

foreach ($locales as $locale => $locale_name):
    switch ($swe_type) {
        case 'text':
            ${'swe_value_' . $locale} = getValue('swe_value_text_' . $locale, 'str', 'POST', '');
            $myform->add('swe_value_' . $locale, 'swe_value_' . $locale, 0, 1, '', 1, 'Chưa nhập value ' . $locale_name);
            break;
        case 'number':
            ${'swe_value_' . $locale} = getValue('swe_value_number_' . $locale, 'str', 'POST', '');
            $myform->add('swe_value_' . $locale, 'swe_value_' . $locale, 0, 1, '', 1, 'Chưa nhập value ' . $locale_name);
            break;
        case 'image':
            if ($upload->common_error == '') {
                $file_name = $upload->file_name;
                if ($file_name != '') {
                    $myform->add('swe_value_' . $locale, 'file_name', 0, 1, '', 1, 'Chưa chọn ảnh');
                }
            }
            break;
        case 'plain_text':
            ${'swe_value_' . $locale} = getValue('swe_value_plain_text_' . $locale, 'str', 'POST', '');
            $myform->add('swe_value_' . $locale, 'swe_value_' . $locale, 0, 1, '', 1, 'Chưa nhập value ' . $locale_name);
            break;
        default:
            break;
    }
endforeach;

$myform->add('swe_label', 'swe_label', 0, 1, '', 1, 'Chưa nhập mô tả');
$myform->add('swe_key', 'swe_key', 0, 1, '', 1, 'Chưa nhập key');
$myform->add('swe_type', 'swe_type', 0, 1, '');
$myform->add('swe_update_time', 'swe_update_time', 1, 1, 1);

$myform->addTable('settings_website');

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {
        $fs_errorMsg .= $myform->checkdata();

        if ($fs_errorMsg == '') {
            //echo $myform->generate_insert_SQL();
            $sqlUpdate = $myform->generate_update_SQL('swe_id', $record_id);
            // _debug($sqlUpdate);die;
            $db_excute = new db_execute($sqlUpdate);

            admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $record_id, 'Sửa cấu hình "' . $swe_label . '""(' . $swe_key . ') giá trị: ' . $swe_value);

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

echo $blade->view()->make('edit', get_defined_vars())->render();
return;
?>