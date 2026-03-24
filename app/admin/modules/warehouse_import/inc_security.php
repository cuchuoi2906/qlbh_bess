<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 50;

$module_name = "Quản lý nhập kho";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);
}

//Declare parameter when insert data
$fs_table = "warehouse";
$id_field = "who_id";
$name_field = "who_product_name";
$fs_filepath = ROOT . "/public/upload/";

$per_page = 20;

$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

// Danh sách đơn vị tính (key = số lưu DB, value = nhãn hiển thị)
$packaging_units = [
    0  => '-- Chọn đơn vị --',
    1  => 'Chai',
    2  => 'Hộp',
    3  => 'Gói'
];

// Danh sách sản phẩm từ bảng products (pro_active = 1)
$products_list = [0 => '-- Chọn sản phẩm --'];
$items_model = \App\Models\Product::where('pro_category_id = 243')
    ->order_by('pro_name_vn', 'ASC');
$_products = $items_model->select_all();
if ($_products) {
    foreach ($_products as $_p) {
        $products_list[$_p->id] = $_p->name_vn . ($_p->code ? ' [' . $_p->code . ']' : '');
    }
}

$views = [
    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>
