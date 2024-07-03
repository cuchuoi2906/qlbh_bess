<?
require_once 'inc_security.php';
checkAddEdit("add");

$ErrorCode = '';

//Khai báo biến khi thêm mới
//$add = "add.php?cat_parent_id=" . getValue('cat_parent_id', 'int', 'GET');
$listing = "listing.php";
//$after_save_data = getValue("after_save_data", "str", "POST", $add);
$fs_title = translate("Thêm mới danh mục");
$fs_action = getURL();
$fs_redirect = 'listing.php';
$fs_errorMsg = "";
$cat_has_child = 0;


// Lấy giá trị từ POST
$myform = new generate_form();

foreach ($locales as $locale => $locale_name):
    ${'pro_name_' . $locale} = getValue('pro_name_' . $locale, 'str', 'POST', '');
    $myform->add('pro_name_' . $locale, 'pro_name_' . $locale, FORM_ADD_TYPE_STRING, 1, '', 1, 'Chưa nhập tên');

    ${'pro_teaser_' . $locale} = getValue('pro_teaser_' . $locale, 'str', 'POST', '');
    $myform->add('pro_teaser_' . $locale, 'pro_teaser_' . $locale, FORM_ADD_TYPE_STRING, 1, '', 1, 'Chưa nhập mô tả ngắn');

    ${'pro_functions_' . $locale} = getValue('pro_functions_' . $locale, 'str', 'POST', '');
    ${'pro_functions_' . $locale} = html_entity_decode(${'pro_functions_' . $locale});
    $myform->add('pro_functions_' . $locale, 'pro_functions_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

    ${'pro_specifications_' . $locale} = getValue('pro_specifications_' . $locale, 'str', 'POST', '');
    ${'pro_specifications_' . $locale} = html_entity_decode(${'pro_specifications_' . $locale});
    $myform->add('pro_specifications_' . $locale, 'pro_specifications_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
endforeach;


$pro_price = getValue('pro_price', 'int', 'POST', 0);
$pro_discount_price = getValue('pro_discount_price', 'int', 'POST', 0);

$pro_active = getValue('pro_active', 'int', 'POST', 1);
$pro_created_at = gmdate("Y-m-d H:i:s", time());

//Call Class generate_form();

$myform->add('pro_price', 'pro_price', FORM_ADD_TYPE_DOUBLE, 1, 0, 1, 'Chưa nhập giá');
$myform->add('pro_discount_price', 'pro_discount_price', FORM_ADD_TYPE_DOUBLE, 1, 0);
$myform->add('pro_active', 'pro_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
$myform->add('pro_created_at', 'pro_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');


$myform->addTable('products');

$myform->evaluate();


$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {
        $db_excute = new db_execute_return();
        $pro_id = $db_excute->db_execute($myform->generate_insert_SQL());
        unset($db_excute);

        //Upload ảnh
        if ($pro_id) {
            $upload = new upload('images', $fs_filepath, $fs_extension, $fs_filesize);
            if ($upload->common_error == '') {

                foreach ($upload->file_name_multi as $filename) {
                    $pri_product_id = $pro_id;
                    $pri_file_name = $filename;
                    $pri_is_avatar = 1;
                    $pri_created_at = gmdate("Y-m-d H:i:s", time());
                    $myform = new generate_form();
                    $myform->add('pri_product_id', 'pri_product_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pro_id, 1);
                    $myform->add('pri_file_name', 'pri_file_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1);
                    $myform->add('pri_is_avatar', 'pri_is_avatar', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pri_is_avatar);
                    $myform->add('pri_created_at', 'pri_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pri_created_at);
                    $myform->addTable('products_images');

                    $myform->evaluate();
                    $db_excute = new db_execute_return();
                    $db_excute->db_execute($myform->generate_insert_SQL());
                }

                redirect($fs_redirect);
            } else {
                $fs_errorMsg = $upload->common_error;
            }
            unset($upload);
        }
    }
}
echo $blade->view()->make('add', get_defined_vars())->render();
?>