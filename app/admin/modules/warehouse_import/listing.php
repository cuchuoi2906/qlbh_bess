<?php

use App\Models\Warehouse;

require_once 'inc_security.php';

// Bộ lọc tìm kiếm
$who_product_name = getValue('who_product_name', 'str', 'GET', '', 3);
$range_date = getValue('who_import_date', 'str', 'GET', '', 3);

$sqlWhere = "1";

if ($who_product_name) {
    $sqlWhere .= " AND who_product_name LIKE '%" . $who_product_name . "%'";
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $date_from = new \DateTime(str_replace('/', '-', $dates[0]));
    $date_to   = isset($dates[1]) ? new \DateTime(str_replace('/', '-', $dates[1])) : $date_from;
    $sqlWhere .= " AND who_import_date BETWEEN '" . $date_from->format('Y-m-d 00:00:00') . "' AND '" . $date_to->format('Y-m-d 23:59:59') . "'";
}
$items = Warehouse::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('who_id', 'DESC')
    ->all();

$total = Warehouse::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'who_id', $per_page);

$dataGrid->column('who_import_date', 'Ngày nhập', function ($row) {
    return $row->import_date ? date('d/m/Y', strtotime($row->import_date)) : '';
},true,true)->addExport();

$dataGrid->column('who_product_name', 'Sản phẩm', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('who_packaging_unit', 'ĐVT', ['string', 'trim'], [],false)->addExport();
$dataGrid->column('who_quantity', 'SL Nhập', 'number', true)->addExport();
$dataGrid->column('who_quantity_packing', 'SL Kiện', 'number', [], false)->addExport();
$dataGrid->column('who_unit_price', 'Giá nhập', 'money', true)->addExport();
$dataGrid->column('who_other_cost', 'CP Khác', 'money', [], false)->addExport();
$dataGrid->column('who_total_price', 'Tổng nhập', 'money', true)->addExport();
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

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render(),
    ])->render();
die;
?>
