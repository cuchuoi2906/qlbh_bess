<?php

use App\Models\SalesExport;

require_once 'inc_security.php';

// Bộ lọc tìm kiếm
$sae_product_name = getValue('sae_product_name', 'str', 'GET', '', 3);
$sae_pro_id_filter = getValue('sae_pro_id', 'int', 'GET', 0);
$range_date = getValue('sae_export_date', 'str', 'GET', '', 3);

$sqlWhere = "1";

if ($sae_pro_id_filter) {
    $sqlWhere .= " AND sae_pro_id = " . intval($sae_pro_id_filter);
} elseif ($sae_product_name) {
    $sqlWhere .= " AND sae_product_name LIKE '%" . $sae_product_name . "%'";
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $date_from = \DateTime::createFromFormat('d/m/Y', trim($dates[0]));
    $date_to   = isset($dates[1]) ? \DateTime::createFromFormat('d/m/Y', trim($dates[1])) : $date_from;
    if ($date_from && $date_to) {
        $sqlWhere .= " AND sae_export_date BETWEEN '" . $date_from->format('Y-m-d 00:00:00') . "' AND '" . $date_to->format('Y-m-d 23:59:59') . "'";
    }
}

$items_model = SalesExport::where($sqlWhere)->order_by('sae_id', 'DESC');

$items = $items_model->pagination(getValue('page'), $per_page)->all();
$total = SalesExport::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'sae_id', $per_page);
$dataGrid->model = SalesExport::where($sqlWhere)->order_by('sae_id', 'DESC');
// Set data export trực tiếp, bypass model pagination để tránh lỗi thiếu bản ghi
$dataGrid->dataExport = SalesExport::where($sqlWhere)->order_by('sae_id', 'DESC')->select_all();

$dataGrid->column('sae_export_date', 'Ngày xuất', function ($row) {
    return $row->export_date ? date('d/m/Y', strtotime($row->export_date)) : '';
}, true, true)->addExport();

$dataGrid->column('sae_product_type', 'Loại xuất', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('sae_product_name', 'Sản phẩm', ['string', 'trim'], true, false)->addExport();
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
$dataGrid->column('sae_export_slip_status', 'TT xuất phiếu', function ($row) use ($export_slip_statuses) {
    $status = (int)($row->export_slip_status ?? 0);
    $label  = $export_slip_statuses[$status] ?? '';
    $color  = $status == 1 ? '#27ae60' : '#e74c3c';
    return '<span style="color:' . $color . ';font-weight:bold">' . $label . '</span>';
})->addExport();

// Cột File: chỉ hiển thị nút Print Preview khi đã xuất phiếu
$dataGrid->column('', 'File', function ($row) {
    $status = (int)($row->export_slip_status ?? 0);
    if ($status != 1) return '';
    $sae_id = (int)$row->id;
    return '<a href="print_slip.php?ids=' . $sae_id . '" target="_blank" '
         . 'class="btn btn-xs btn-info" title="Xem & In phiếu">'
         . '<i class="fa fa-print"></i> Print Preview</a>';
});

// Tính tổng (chỉ khi không export, tránh clone corrupt model)
if (getValue('export', 'str') != 'Export') {
    $total_model = clone $items_model;
    $total_qty = $total_model->sum('sae_quantity_ban')->select();
    $total_qty = $total_qty->total ?? 0;

    $total_model2 = clone $items_model;
    $total_ban = $total_model2->sum('sae_total_ban')->select();
    $total_ban = $total_ban->total ?? 0;

    $dataGrid->total('sae_quantity_ban', $total_qty);
    $dataGrid->total('sae_total_ban', $total_ban, 'đ');
}

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render(),
        'products_list' => $products_list,
        'sae_pro_id_filter' => $sae_pro_id_filter,
    ])->render();
die;
?>
