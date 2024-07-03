<?
require_once 'inc_security.php';
checkAddEdit("add");

$ErrorCode = '';
$myform = new generate_form();

//Khai báo biến khi thêm mới
$add = "add.php?type=" . getValue('type', 'str');
$listing = "listing.php";
//$after_save_data = getValue("after_save_data", "str", "POST", $add);
$fs_title = translate("Thêm mới bài viết");
$fs_action = getURL();

$fs_errorMsg = "";

// Lấy giá trị từ POST

foreach ($locales as $locale => $locale_name):
    ${'pos_title_' . $locale} = getValue('pos_title_' . $locale, 'str', 'POST', '');
    $myform->add('pos_title_' . $locale, 'pos_title_' . $locale, 0, 1, '', 1, 'Chưa nhập tiêu đề');

    ${'pos_teaser_' . $locale} = getValue('pos_teaser_' . $locale, 'str', 'POST', '');
    if ($pos_type == 'FAQ') {
        $myform->add('pos_teaser_' . $locale, 'pos_teaser_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
    } else {
        $myform->add('pos_teaser_' . $locale, 'pos_teaser_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1, 'Chưa nhập mô tả ngắn');
    }
    ${'pos_content_' . $locale} = getValue('pos_content_' . $locale, 'str', 'POST', '');
    $myform->add('pos_content_' . $locale, 'pos_content_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1, 'Chưa nhập nội dung');
endforeach;

$pos_category_id = getValue('pos_category_id', 'int', 'POST', 0);

$pos_active = getValue('pos_active', 'int', 'POST', 1);
$pos_is_hot = getValue('pos_is_hot', 'int', 'POST', 1);
$pos_show_home = getValue('pos_show_home', 'int', 'POST', 1);

$pos_seo_title = getValue('pos_seo_title', 'str', 'POST', '');
$pos_seo_keyword = getValue('pos_seo_keyword', 'str', 'POST', '');
$pos_seo_description = getValue('pos_seo_description', 'str', 'POST', '');

$pos_rewrite = removeTitle($pos_title_vn);
$pos_created_at = date('Y-m-d H:i:s');


//Call Class generate_form();

$myform->add('pos_category_id', 'pos_category_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0, 1, 'Chưa chọn danh mục');

$myform->add('pos_rewrite', 'pos_rewrite', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

$myform->add('pos_active', 'pos_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);
$myform->add('pos_is_hot', 'pos_is_hot', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pos_is_hot);
$myform->add('pos_show_home', 'pos_show_home', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pos_show_home);


$myform->add('pos_seo_title', 'pos_seo_title', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_title_vn);
$myform->add('pos_seo_keyword', 'pos_seo_keyword', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_title_vn);
$myform->add('pos_seo_description', 'pos_seo_description', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_teaser_vn);
//$myform->add('pos_created_at', 'pos_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_created_at);

$myform->add('pos_type', 'pos_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pos_type);

$myform->addTable('posts');

$myform->evaluate();


$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    $upload = new upload('pos_image', $fs_filepath, $fs_extension, $fs_filesize);
    if ($upload->common_error == '') {
        $file_name = $upload->file_name;
        $myform->add('pos_image', 'file_name', 0, 1, '');
    }
    unset($upload);
    if ($fs_errorMsg == '') {
        $db_excute = new db_execute($myform->generate_insert_SQL());
        unset($db_excute);
        redirect($fs_redirect);
    }
}
echo $blade->view()->make('add', get_defined_vars())->render();
?>