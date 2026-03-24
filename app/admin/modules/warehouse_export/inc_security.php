<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 51;

$module_name = "Quản lý xuất kho";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);
}

//Declare parameter when insert data
$fs_table = "sales_export";
$id_field = "sae_id";
$name_field = "sae_product_name";
$fs_filepath = ROOT . "/public/upload/";

$per_page = 20;

$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

// Danh sách loại xuất — cấu hình tại đây
$export_types = [
    ''            => '-- Chọn loại xuất --',
    'Bán lẻ'      => 'Bán lẻ',
    'Bán sỉ'      => 'Bán sỉ',
    'Xuất nội bộ' => 'Xuất nội bộ',
    'Xuất hủy'    => 'Xuất hủy',
];

// Danh sách sản phẩm từ bảng products
$products_list = [0 => '-- Chọn sản phẩm --'];
$_products = \App\Models\Product::where('pro_category_id = 243 AND pro_deleted_at IS NULL')
    ->order_by('pro_name_vn', 'ASC')
    ->select_all();
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
