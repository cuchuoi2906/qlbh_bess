<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 106;

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

// Trạng thái xuất phiếu giao hàng
$export_slip_statuses = [
    0 => 'Chưa xuất phiếu',
    1 => 'Đã xuất phiếu',
];

// Danh sách sản phẩm từ bảng products (giữ lại để backward-compat edit cũ)
$products_list = [0 => '-- Chọn sản phẩm --'];
$_products = \App\Models\Product::where('pro_category_id = 243 AND pro_deleted_at IS NULL')
    ->order_by('pro_name_vn', 'ASC')
    ->select_all();
if ($_products) {
    foreach ($_products as $_p) {
        $products_list[$_p->id] = $_p->name_vn . ($_p->code ? ' [' . $_p->code . ']' : '');
    }
}

// Danh sách phiếu nhập kho (tính tồn thực tế theo who_id)
$warehouse_imports_list = [0 => '-- Chọn phiếu nhập kho --'];
$warehouse_imports_data  = [];
$_sql_who = "SELECT w.who_id, w.who_pro_id, w.who_product_name, w.who_import_date,
                    w.who_quantity, w.who_lot_number, w.who_packaging_unit,
                    w.who_mfg_date, w.who_exp_date,
                    w.who_unit_price, w.who_other_cost,
                    (w.who_quantity * w.who_unit_price) + COALESCE(w.who_other_cost, 0) AS who_total_import,
                    COALESCE(SUM(s.sae_quantity_ban), 0) AS total_sold
             FROM warehouse w
             LEFT JOIN sales_export s ON s.sae_who_id = w.who_id
             GROUP BY w.who_id
             ORDER BY w.who_import_date DESC, w.who_id DESC";
$_who_db = new db_query($_sql_who);
if ($_who_db->result) {
    while ($_w = mysqli_fetch_object($_who_db->result)) {
        $ton   = (int)$_w->who_quantity - (int)$_w->total_sold;
        $label = date('d/m/Y', strtotime($_w->who_import_date)) . ' - ' . $_w->who_product_name;
        if ($_w->who_lot_number) {
            $label .= ' [Lô: ' . $_w->who_lot_number . ']';
        }
        $total_import = (float)$_w->who_total_import;
        $label .= ' (Tồn: ' . $ton . ' | Tổng nhập: ' . number_format($total_import, 0, ',', '.') . 'đ)';
        $warehouse_imports_list[(int)$_w->who_id] = $label;
        $warehouse_imports_data[(int)$_w->who_id] = [
            'pro_id'       => (int)$_w->who_pro_id,
            'name'         => (string)$_w->who_product_name,
            'stock'        => $ton,
            'total_import' => $total_import,
            'lot'          => (string)$_w->who_lot_number,
            'mfg'          => $_w->who_mfg_date ? date('d/m/Y', strtotime($_w->who_mfg_date)) : '',
            'exp'          => $_w->who_exp_date ? date('d/m/Y', strtotime($_w->who_exp_date)) : '',
        ];
    }
}
unset($_who_db);

$views = [
    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>
