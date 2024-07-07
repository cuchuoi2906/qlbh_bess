<?php
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

//    ${'pro_specifications_' . $locale} = getValue('pro_specifications_' . $locale, 'str', 'POST', '');
    ${'pro_specifications_' . $locale} = '';
    $myform->add('pro_specifications_' . $locale, 'pro_specifications_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
endforeach;


$pro_price = getValue('pro_price', 'int', 'POST', 0);
$pro_discount_price = getValue('pro_discount_price', 'int', 'POST', 0);

$pro_active = getValue('pro_active', 'int', 'POST', 1);
$pro_is_hot = getValue('pro_is_hot', 'int', 'POST', 1);
$pro_created_at = gmdate("Y-m-d H:i:s", time());

$pro_quantity = getValue('pro_quantity', 'int', 'POST', 0);

$pro_code = getValue('pro_code', 'str', 'POST', '');
$myform->add('pro_code', 'pro_code', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pro_code, 1, 'Bạn chưa nhập mã sản phẩm', 1, 'Mã sản phẩm đã tồn tại. Vui lòng kiểm tra lại');

$pro_barcode = getValue('pro_barcode', 'str', 'POST', '');
$myform->add('pro_barcode', 'pro_barcode', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pro_barcode, 0, 'Bạn chưa nhập barcode', 1, 'Barcode đã tồn tại. Vui lòng kiểm tra lại');

$pro_commission = getValue('pro_commission', 'int', 'POST', 0);

//$pro_commission = ($pro_commission > 0 && $pro_commission < 100) ? $pro_commission : 0;
$pro_commission = ($pro_commission >= 0 && $pro_commission < $pro_price) ? $pro_commission : 0;


$pro_category_id = getValue('pro_category_id', 'int', 'POST', 0);
$cate_type = App\Models\Categories\Category::findByID($pro_category_id);
if($cate_type){
    $myform->add('pro_type', 'pro_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $cate_type->cat_type,0);
}
$myform->add('pro_category_id', 'pro_category_id', FORM_ADD_TYPE_DOUBLE, 1, 0, 1, 'Chưa chọn danh mục');
$myform->add('pro_quantity', 'pro_quantity', FORM_ADD_TYPE_INT, 1, 0);
$myform->add('pro_commission', 'pro_commission', FORM_ADD_TYPE_INT, 1, 0);
$myform->add('pro_price', 'pro_price', FORM_ADD_TYPE_DOUBLE, 1, 0, 1, 'Chưa nhập giá');
$myform->add('pro_discount_price', 'pro_discount_price', FORM_ADD_TYPE_DOUBLE, 1, 0);
$myform->add('pro_active', 'pro_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
$myform->add('pro_active_inventory', 'pro_active_inventory', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
$myform->add('pro_is_hot', 'pro_is_hot', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);

$pro_commission_plan_id = getValue('pro_commission_plan_id', 'int', 'POST', 0);
$myform->add('pro_commission_plan_id', 'pro_commission_plan_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
//$myform->add('pro_created_at', 'pro_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

$pro_point = getValue('pro_point', 'int', 'POST', 0);
$myform->add('pro_point', 'pro_point', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pro_point);
$pro_point = getValue('pro_point', 'int', 'POST', 0);

$pro_brand_id = getValue('pro_brand_id', 'int', 'POST', 0);
$myform->add('pro_brand_id', 'pro_brand_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1,0,'Bạn chưa nhập thương hiệu');

//Sort
$max = \App\Models\Product::fields('MAX(pro_order) AS max_order')->first();
$pro_order = $max->max_order ?? 0;
$pro_order++;
$myform->add('pro_order', 'pro_order', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pro_order);

$video = new uploadVideo('videoFile', $fs_filepath_video, $fs_extension_video, $fs_filesize_video);
if($video->file_name !='' && empty($video->common_error)){
    $pro_video_file_name = $video->file_name;
    $myform->add('pro_video_file_name', 'pro_video_file_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pro_video_file_name);
}


$myform->addTable('products');

$myform->evaluate();


$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {
        $upload = new upload('images', $fs_filepath, $fs_extension, $fs_filesize);
        if ($upload->common_error == '') {
            $db_excute = new db_execute_return();
            $pro_id = $db_excute->db_execute($myform->generate_insert_SQL());
            unset($db_excute);

            admin_log($admin_id, ADMIN_LOG_ACTION_ADD, $pro_id, 'Đã thêm sản phẩm ' . $pro_name_vn . '(' . $pro_id . ')');

            if ($pro_id) {
                $i = 0;
                foreach ($upload->file_name_multi as $filename) {
                    $pri_product_id = $pro_id;
                    $pri_file_name = $filename;
                    $pri_is_avatar = $i == 0 ? 1 : 0;
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
                    $i++;
                }
                redirect($fs_redirect);
                unset($upload);
            }
        } else {
            $fs_errorMsg = $upload->common_error;
        }


    }
}
echo $blade->view()->make('add', get_defined_vars())->render();
?>