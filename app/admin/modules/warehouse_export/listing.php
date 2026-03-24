<?php

use App\Models\SalesExport;

require_once 'inc_security.php';

// Bộ lọc tìm kiếm
$sae_product_name = getValue('sae_product_name', 'str', 'GET', '', 3);
$range_date = getValue('sae_export_date', 'str', 'GET', '', 3);

$sqlWhere = "1";

if ($sae_product_name) {
    $sqlWhere .= " AND sae_product_name LIKE '%" . $sae_product_name . "%'";
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $date_from = new \DateTime(str_replace('/', '-', $dates[0]));
    $date_to   = isset($dates[1]) ? new \DateTime(str_replace('/', '-', $dates[1])) : $date_from;
    $sqlWhere .= " AND sae_export_date BETWEEN '" . $date_from->format('Y-m-d 00:00:00') . "' AND '" . $date_to->format('Y-m-d 23:59:59') . "'";
}

$items = SalesExport::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('sae_id', 'DESC')
    ->all();

$total = SalesExport::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'sae_id', $per_page);

$dataGrid->column('sae_export_date', 'Ngày xuất', function ($row) {
    return $row->export_date ? date('d/m/Y', strtotime($row->export_date)) : '';
}, true, true)->addExport();

$dataGrid->column('sae_product_type', 'Loại xuất', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('sae_product_name', 'Sản phẩm', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('sae_quantity_ban', 'SL Bán', 'number', true)->addExport();
$dataGrid->column('sae_unit_price', 'Giá bán', 'money', true)->addExport();
$dataGrid->column('sae_other_cost', 'CP Khác', 'money', [], false)->addExport();
$dataGrid->column('sae_total_ban', 'Tổng Bán', 'money', true)->addExport();
$dataGrid->column('sae_customer_name', 'Khách hàng', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('sae_customer_phone', 'SĐT', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('sae_lot_number', 'Số lô/LOT', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('sae_mfg_date', 'Ngày SX(MFG)', function ($row) {
    return $row->mfg_date ? date('d/m/Y', strtotime($row->mfg_date)) : '';
})->addExport();
$dataGrid->column('sae_exp_date', 'Hạn dùng EXP', function ($row) {
    return $row->exp_date ? date('d/m/Y', strtotime($row->exp_date)) : '';
})->addExport();
$dataGrid->column('sae_note', 'Ghi chú', ['string', 'trim'], [], false)->addExport();

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render(),
    ])->render();
die;
?>
