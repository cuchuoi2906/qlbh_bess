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

    ${'pos_teaser_' . $locale} = getValue('pos_teaser_' . $locale, 'str', 'POST', '');
    $myform->add('pos_teaser_' . $locale, 'pos_teaser_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1, 'Chưa nhập mô tả ngắn');

    ${'pos_content_' . $locale} = getValue('pos_content_' . $locale, 'str', 'POST', '');
    $myform->add('pos_content_' . $locale, 'pos_content_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1, 'Chưa nhập nội dung');
endforeach;

$pos_category_id = getValue('pos_category_id', 'int', 'POST', 0);


$pos_active = getValue('pos_active', 'int', 'POST', 0);
$pos_is_hot = getValue('pos_is_hot', 'int', 'POST', 1);
$pos_show_home = getValue('pos_show_home', 'int', 'POST', 1);

$pos_seo_title = getValue('pos_seo_title', 'str', 'POST', '');
$pos_seo_keyword = getValue('pos_seo_keyword', 'str', 'POST', '');
$pos_seo_description = getValue('pos_seo_description', 'str', 'POST', '');

$pos_total_view = getValue('pos_total_view', 'int', 'POST', 1);

$pos_rewrite = removeTitle($pos_title_vn);

$pos_updated_at = date('Y-m-d H:i:s');


//Call Class generate_form();
$myform->add('pos_category_id', 'pos_category_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0, 1, 'Chưa chọn danh mục');
$myform->add('pos_rewrite', 'pos_rewrite', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
//$myform->add('pos_type', 'pos_type', 0, 1, $pos_type, 1);
$myform->add('pos_active', 'pos_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);
$myform->add('pos_is_hot', 'pos_is_hot', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);
$myform->add('pos_show_home', 'pos_show_home', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);


$myform->add('pos_total_view', 'pos_total_view', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pos_total_view);

//$myform->add('pos_updated_at', 'pos_updated_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_updated_at);


$myform->add('pos_seo_title', 'pos_seo_title', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_title_vn);
$myform->add('pos_seo_keyword', 'pos_seo_keyword', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_title_vn);
$myform->add('pos_seo_description', 'pos_seo_description', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_teaser_vn);

$myform->addTable('posts');

$myform->evaluate();


//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    $upload = new upload('pos_image', $fs_filepath, $fs_extension, $fs_filesize);
    if ($upload->common_error == '') {
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

echo $blade->view()->make('edit', get_defined_vars())->render();
return;
?>