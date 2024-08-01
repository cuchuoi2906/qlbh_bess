<?php
use App\Models\Product;

require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

$record = Product::findByID($record_id);

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin danh mục";
$fs_action = getURL();
$fs_errorMsg = "";

// Lấy giá trị từ POST
$myform = new generate_form();
$myform->addRecordID('pro_id', $record_id);

foreach ($locales as $locale => $locale_name):
    ${'pro_name_' . $locale} = getValue('pro_name_' . $locale, 'str', 'POST', '');
    $myform->add('pro_name_' . $locale, 'pro_name_' . $locale, FORM_ADD_TYPE_STRING, 1, '', 1, 'Chưa nhập tên');

    ${'pro_teaser_' . $locale} = getValue('pro_teaser_' . $locale, 'str', 'POST', '');
    $myform->add('pro_teaser_' . $locale, 'pro_teaser_' . $locale, FORM_ADD_TYPE_STRING, 1, '', 1, 'Chưa nhập mô tả ngắn');

    ${'pro_functions_' . $locale} = getValue('pro_functions_' . $locale, 'str', 'POST', '');
    ${'pro_functions_' . $locale} = html_entity_decode(${'pro_functions_' . $locale});
    $myform->add('pro_functions_' . $locale, 'pro_functions_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

//    ${'pro_specifications_' . $locale} = getValue('pro_specifications_' . $locale, 'str', 'POST', '');
//    ${'pro_specifications_' . $locale} = html_entity_decode(${'pro_specifications_' . $locale});
//    $myform->add('pro_specifications_' . $locale, 'pro_specifications_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
    ${'pro_specifications_' . $locale} = '';
    $myform->add('pro_specifications_' . $locale, 'pro_specifications_' . $locale, FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');
endforeach;

$pro_price = getValue('pro_price', 'int', 'POST', 0);
$pro_discount_price = getValue('pro_discount_price', 'int', 'POST', 0);
$pro_active = getValue('pro_active', 'int', 'POST', 1);
$pro_is_hot = getValue('pro_is_hot', 'int', 'POST', 1);
$pro_quantity = getValue('pro_quantity', 'int', 'POST', 0);

$pro_commission = getValue('pro_commission', 'int', 'POST', 0);
//$pro_commission = ($pro_commission > 0 && $pro_commission < 100) ? $pro_commission : 0;
$pro_commission = ($pro_commission >= 0 && $pro_commission < $pro_price) ? $pro_commission : 0;

$pro_edited_at = gmdate("Y-m-d H:i:s", time());
$pro_category_id = getValue('pro_category_id', 'int', 'POST', 0);

$pro_code = getValue('pro_code', 'str', 'POST', '');
$myform->add('pro_code', 'pro_code', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pro_code, 1, 'Bạn chưa nhập mã sản phẩm', 1, 'Mã sản phẩm đã tồn tại. Vui lòng kiểm tra lại');

$pro_barcode = getValue('pro_barcode', 'str', 'POST', '');
$myform->add('pro_barcode', 'pro_barcode', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pro_barcode, 0, 'Bạn chưa nhập barcode', 1, 'Barcode đã tồn tại. Vui lòng kiểm tra lại');

$myform->add('pro_category_id', 'pro_category_id', FORM_ADD_TYPE_DOUBLE, 1, 0, 1, 'Chưa chọn danh mục');
$myform->add('pro_price', 'pro_price', FORM_ADD_TYPE_DOUBLE, 1, 0, 1, 'Chưa nhập giá');
$myform->add('pro_discount_price', 'pro_discount_price', FORM_ADD_TYPE_DOUBLE, 1, 0);
$myform->add('pro_quantity', 'pro_quantity', FORM_ADD_TYPE_INT, 1, 0);
$myform->add('pro_commission', 'pro_commission', FORM_ADD_TYPE_INT, 1, 0);
$myform->add('pro_active', 'pro_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
$pro_active_inventory = getValue('pro_active_inventory', 'str', 'POST', 1);
$myform->add('pro_active_inventory', 'pro_active_inventory', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pro_active_inventory,1);
$myform->add('pro_is_hot', 'pro_is_hot', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
//$myform->add('pro_updated_at', 'pro_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '');

$pro_commission_plan_id = getValue('pro_commission_plan_id', 'int', 'POST', 0);
$myform->add('pro_commission_plan_id', 'pro_commission_plan_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);


$pro_brand_id = getValue('pro_brand_id', 'int', 'POST', 0);
//$myform->add('pro_name_' . $locale, 'pro_name_' . $locale, FORM_ADD_TYPE_STRING, 1, '', 1, 'Chưa nhập tên');

$myform->add('pro_brand_id', 'pro_brand_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);

$pro_point = getValue('pro_point', 'int', 'POST', 0);
$myform->add('pro_point', 'pro_point', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pro_point);
//Sort
if ($record->order == 0) {
    $max = \App\Models\Product::fields('MAX(pro_order) AS max_order')->first();
    $pro_order = $max->max_order ?? 0;
    $pro_order++;
    $myform->add('pro_order', 'pro_order', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pro_order);
}

# Upload file video
$pro_video_file_name = getValue('hdn_video_name', 'str', 'POST', '');
$video = new uploadVideo('videoFile', $fs_filepath_video, $fs_extension_video, $fs_filesize_video);
if($video->file_name !='' && empty($video->common_error)){
    $pro_video_file_name = $video->file_name;
}
$myform->add('pro_video_file_name', 'pro_video_file_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pro_video_file_name);
unset($video);

//pre($myform);die;
$myform->addTable('products');


$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();
        if ($fs_errorMsg == '') {

            $sqlUpdate = $myform->generate_update_SQL('pro_id', $record_id);
            //var_Dump($sqlUpdate);die;
            //_debug($sqlUpdate);die;
            $db_excute = new db_execute($sqlUpdate);
            unset($db_excute);

            admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $record_id, 'Đã sửa thông tin của sản phẩm ' . $pro_name_vn);

            $upload = new upload('images', $fs_filepath, $fs_extension, $fs_filesize);

            if ($upload->common_error == '') {

                $product = Product::findByID($record_id);
                $images = $product->images;

                foreach ($upload->file_name_multi as $i => $filename) {
                    $pri_product_id = $record_id;
                    $pri_file_name = $filename;
                    $pri_is_avatar = 0;
                    if (!$images || $images->count() <= 0) {
                        $pri_is_avatar = $i == 0 ? 1 : 0;
                    }
                    $pri_created_at = gmdate("Y-m-d H:i:s", time());
                    $myform = new generate_form();
                    $myform->add('pri_product_id', 'pri_product_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $record_id, 1);
                    $myform->add('pri_file_name', 'pri_file_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1);
                    $myform->add('pri_is_avatar', 'pri_is_avatar', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $pri_is_avatar);
                    $myform->add('pri_created_at', 'pri_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pri_created_at);
                    $myform->addTable('products_images');

                    $myform->evaluate();
                    $db_excute = new db_execute_return();
                    $db_excute->db_execute($myform->generate_insert_SQL());
                }
            } else {
//                $fs_errorMsg = $upload->common_error;
            }
            # upload file video
             
            unset($upload);

            if ($fs_errorMsg == '') {
                \AppView\Helpers\Facades\FlashMessage::success('Cập nhật thành công', url_back());
            }
        }

    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

//lay du lieu cua record can sua doi
$record = Product::findByID($record_id);
if ($record) {
    foreach ($record->toArray(false) as $key => $value) {
        if ($key != 'lang_id' && $key != 'admin_id')
            $$key = $value;
    }
} else {
    exit();
}
unset($db_data);

$images = $record->images;

echo $blade->view()->make('edit', get_defined_vars())->render();
return;
?>