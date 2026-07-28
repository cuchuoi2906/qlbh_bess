<?php

use App\Models\Warehouse;

require_once 'inc_security.php';

// Bộ lọc tìm kiếm
$who_product_name = getValue('who_product_name', 'str', 'GET', '', 3);
$who_pro_id_filter = getValue('who_pro_id', 'int', 'GET', 0);
$range_date = getValue('who_import_date', 'str', 'GET', '', 3);

$sqlWhere = "1";

if ($who_pro_id_filter) {
    $sqlWhere .= " AND who_pro_id = " . intval($who_pro_id_filter);
} elseif ($who_product_name) {
    $sqlWhere .= " AND who_product_name LIKE '%" . $who_product_name . "%'";
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $date_from = \DateTime::createFromFormat('d/m/Y', trim($dates[0]));
    $date_to   = isset($dates[1]) ? \DateTime::createFromFormat('d/m/Y', trim($dates[1])) : $date_from;
    if ($date_from && $date_to) {
        $sqlWhere .= " AND who_import_date BETWEEN '" . $date_from->format('Y-m-d 00:00:00') . "' AND '" . $date_to->format('Y-m-d 23:59:59') . "'";
    }
}
$items_model = Warehouse::where($sqlWhere)->order_by('who_id', 'DESC');

$items = $items_model->pagination(getValue('page'), $per_page)->all();
$total = Warehouse::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'who_id', $per_page);
$dataGrid->model = Warehouse::where($sqlWhere)->order_by('who_id', 'DESC');
$dataGrid->dataExport = Warehouse::where($sqlWhere)->order_by('who_id', 'DESC')->select_all();

$dataGrid->column('who_import_date', 'Ngày nhập', function ($row) {
    return $row->import_date ? date('d/m/Y', strtotime($row->import_date)) : '';
},true,true)->addExport();

$dataGrid->column('who_product_name', 'Sản phẩm', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('who_packaging_unit', 'ĐVT', function ($row) use ($packaging_units) {

    $val = intval($row->packaging_unit);
    return isset($packaging_units[$val]) ? $packaging_units[$val] : $val;
}, [], false)->addExport();
$dataGrid->column('who_quantity', 'SL Nhập', 'number', true)->addExport();
$dataGrid->column('who_quantity_packing', 'SL Kiện', 'number', [], false)->addExport();
$dataGrid->column('who_unit_price', 'Giá nhập', 'money', true)->addExport();
$dataGrid->column('who_other_cost', 'CP Khác', 'money', [], false)->addExport();
$dataGrid->column('who_total_price', 'Tổng nhập', function ($row) {
    $total = ($row->quantity * $row->unit_price) + $row->other_cost;
    return number_format($total, 0, ',', '.');
}, true)->addExport();
$dataGrid->column('who_supplier_name', 'NCC', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('who_lot_number', 'Số lô/LOT', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('who_mfg_date', 'Ngày SX(MFG)', function ($row) {
    return $row->mfg_date ? date('d/m/Y', strtotime($row->mfg_date)) : '';
})->addExport();
$dataGrid->column('who_exp_date', 'Hạn dùng EXP', function ($row) {
    return $row->exp_date ? date('d/m/Y', strtotime($row->exp_date)) : '';
})->addExport();
$dataGrid->column('who_receiver_name', 'Người nhận', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('who_warehouse_name', 'Kho nhận', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('who_note', 'Ghi chú', ['string', 'trim'], [], false)->addExport();

// Tính tổng (chỉ khi không export, tránh clone corrupt model)
if (getValue('export', 'str') != 'Export') {
    $total_model = clone $items_model;
    $total_qty = $total_model->sum('who_quantity')->select();
    $total_qty = $total_qty->total ?? 0;

    // Tính tổng nhập = SUM(SL nhập * Giá nhập + CP Khác) bằng raw query
    $sql_total = "SELECT SUM((who_quantity * who_unit_price) + COALESCE(who_other_cost, 0)) AS total FROM warehouse WHERE " . $sqlWhere;
    $_db_total = new db_query($sql_total);
    $total_price = 0;
    if ($_db_total->result) {
        $_r = mysqli_fetch_object($_db_total->result);
        $total_price = $_r->total ?? 0;
    }
    unset($_db_total);

    $dataGrid->total('who_quantity', $total_qty);
    $dataGrid->total('who_total_price', $total_price, 'đ');
}

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render(),
        'products_list' => $products_list,
        'who_pro_id_filter' => $who_pro_id_filter,
    ])->render();
die;
?>
