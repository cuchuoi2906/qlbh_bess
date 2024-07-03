<?
require_once 'inc_security.php';
checkAddEdit("add");

$ErrorCode = '';
$myform = new generate_form();

//Khai báo biến khi thêm mới
$add = "add_driver.php";
$listing = "listing.php";
//$after_save_data = getValue("after_save_data", "str", "POST", $add);
$fs_title = translate("Thêm mới bài viết");
$fs_action = getURL();

$fs_errorMsg = "";

// Lấy giá trị từ POST

foreach ($locales as $locale => $locale_name):
    ${'pos_title_' . $locale} = getValue('pos_title_' . $locale, 'str', 'POST', '');
    $myform->add('pos_title_' . $locale, 'pos_title_' . $locale, 0, 1, '');
endforeach;

$pos_category_id = getValue('pos_category_id', 'int', 'POST', 0);
$pos_product_id = getValue('pos_product_id', 'int', 'POST', 0);

$pos_active = getValue('pos_active', 'int', 'POST', 1);
$pos_file_size = getValue('pos_active', 'str', 'POST', '');


$pos_rewrite = removeTitle($pos_title_vn);
$pos_created_at = date('Y-m-d H:i:s');


//Call Class generate_form();

$myform->add('pos_category_id', 'pos_category_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0, 1, 'Chưa chọn danh mục');
$myform->add('pos_product_id', 'pos_product_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0, 1, 'Chưa chọn sản phẩm');

$myform->add('pos_rewrite', 'pos_rewrite', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

$myform->add('pos_active', 'pos_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);

$myform->add('pos_type', 'pos_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, 'DRIVER');

$myform->addTable('posts');

$myform->evaluate();


$action = getValue("action", "str", "POST", "");

if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {

        $upload = new upload('pos_file_size', $fs_driver_filepath, $fs_driver_extension, $fs_driver_filesize);
        $fs_errorMsg = $upload->common_error;
        if ($fs_errorMsg == '') {
            $file_size = formatSizeUnits($upload->file_size);
            $myform->add('pos_file_size', 'file_size', 0, 1, '', 1, 'Chưa tải file driver lên');

            $file_name = $upload->file_name;
            $myform->add('pos_image', 'file_name', 0, 1, '');
            unset($upload);
            $db_excute = new db_execute($myform->generate_insert_SQL());
            unset($db_excute);
            echo 'Thêm thành công!';
            redirect($fs_redirect);
        }
    }
}

echo $blade->view()->make('add_driver', get_defined_vars())->render();
?>