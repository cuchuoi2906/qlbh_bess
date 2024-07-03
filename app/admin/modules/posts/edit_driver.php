<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");
$myform = new generate_form();

//$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php?")));
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin danh mục";
$fs_action = getURL();
$fs_errorMsg = "";


// Lấy giá trị từ POST
foreach ($locales as $locale => $locale_name):
    ${'pos_title_' . $locale} = getValue('pos_title_' . $locale, 'str', 'POST', '');
    $myform->add('pos_title_' . $locale, 'pos_title_' . $locale, 0, 1, '', 1, 'Chưa nhập tiêu đề');
endforeach;

$pos_category_id = getValue('pos_category_id', 'int', 'POST', 0);
$pos_product_id = getValue('pos_product_id', 'int', 'POST', 0);


$pos_active = getValue('pos_active', 'int', 'POST', 0);
$pos_file_size = getValue('pos_active', 'str', 'POST', '');

$pos_rewrite = removeTitle($pos_title_vn);

$pos_updated_at = date('Y-m-d H:i:s');


//Call Class generate_form();
$myform->add('pos_category_id', 'pos_category_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0, 1, 'Chưa chọn danh mục');
$myform->add('pos_product_id', 'pos_product_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0, 1, 'Chưa chọn sản phẩm');

$myform->add('pos_rewrite', 'pos_rewrite', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
//$myform->add('pos_type', 'pos_type', 0, 1, 'DRIVER', 1);
$myform->add('pos_active', 'pos_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);

$myform->addTable('posts');

$myform->evaluate();


//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();
    $upload = new upload('pos_file_size', $fs_driver_filepath, $fs_driver_extension, $fs_driver_filesize);

    if ($upload->common_error == '') {
        $file_size = formatSizeUnits($upload->file_size);
        $myform->add('pos_file_size', 'file_size', 0, 1, '');

        $file_name = $upload->file_name;
        $myform->add('pos_image', 'file_name', 0, 1, '');
    }
    unset($upload);
    if ($fs_errorMsg == '') {
        $db_excute = new db_execute($myform->generate_update_SQL('pos_id', $record_id));
        unset($db_excute);
        redirect($fs_redirect);
    }

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

echo $blade->view()->make('edit_driver', get_defined_vars())->render();
return;
?>