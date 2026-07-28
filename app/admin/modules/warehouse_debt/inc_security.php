<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

$module_id = 110;
$module_name = "Quản lý công nợ";

checkLogged();

if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);
}

$fs_table = "sales_export";
$id_field = "sae_id";
$name_field = "sae_product_name";

$per_page = 20;

// Trạng thái thanh toán
$payment_statuses = [
    'Chưa thanh toán'      => 'Chưa thanh toán',
    'Thanh toán một phần'  => 'Thanh toán một phần',
    'Đã thanh toán'        => 'Đã thanh toán',
];

// Đơn vị tính
$packaging_units = [
    0 => '',
    1 => 'Chai',
    2 => 'Hộp',
    3 => 'Gói',
];

// Danh sách sản phẩm từ bảng products
$products_list = [0 => '-- Chọn sản phẩm --'];
$_products = \App\Models\Product::where('pro_category_id = 243')
    ->order_by('pro_name_vn', 'ASC')
    ->select_all();
if ($_products) {
    foreach ($_products as $_p) {
        $products_list[$_p->id] = $_p->name_vn . ($_p->code ? ' [' . $_p->code . ']' : '');
    }
}

// Map who_id -> packaging_unit (để hiển thị ĐVT từ phiếu nhập)
$packaging_map = [];
$_pkg_db = new db_query("SELECT who_id, who_packaging_unit FROM warehouse");
if ($_pkg_db->result) {
    while ($_r = mysqli_fetch_object($_pkg_db->result)) {
        $packaging_map[(int)$_r->who_id] = (int)$_r->who_packaging_unit;
    }
}
unset($_pkg_db);

$views = [
    dirname(__FILE__) . '/views',
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>
