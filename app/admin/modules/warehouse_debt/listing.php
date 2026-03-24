<?php

use App\Models\SalesExport;

require_once 'inc_security.php';

$fs_title = "Quản lý công nợ";

// --- Filters ---
$search    = getValue('search', 'str', 'GET', '', 3);
$date_from = getValue('date_from', 'str', 'GET', '');
$date_to   = getValue('date_to', 'str', 'GET', '');

$sqlWhere = "1";

if ($search) {
    $searchSafe = addslashes($search);
    $sqlWhere .= " AND (sae_product_name LIKE '%{$searchSafe}%' OR sae_customer_name LIKE '%{$searchSafe}%')";
}
if ($date_from) {
    $d = DateTime::createFromFormat('d/m/Y', $date_from);
    if ($d) $sqlWhere .= " AND sae_export_date >= '" . $d->format('Y-m-d') . "'";
}
if ($date_to) {
    $d = DateTime::createFromFormat('d/m/Y', $date_to);
    if ($d) $sqlWhere .= " AND sae_export_date <= '" . $d->format('Y-m-d') . "'";
}

// --- Query danh sách công nợ ---
$items = SalesExport::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('sae_export_date', 'DESC')
    ->all();

$total = SalesExport::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'sae_id', $per_page);

$dataGrid->column('sae_export_date', 'Ngày mua', function ($row) {
    return $row->export_date ? date('d/m/Y', strtotime($row->export_date)) : '';
}, true)->addExport();

$dataGrid->column('sae_customer_name', 'Khách hàng', ['string', 'trim'], true)->addExport();
$dataGrid->column('sae_customer_phone', 'Số điện thoại', ['string', 'trim'])->addExport();
$dataGrid->column('sae_product_name', 'Sản phẩm', ['string', 'trim'], true)->addExport();
$dataGrid->column('sae_product_type', 'ĐVT', ['string', 'trim'])->addExport();
$dataGrid->column('sae_quantity_ban', 'SL SP mua', 'number', true)->addExport();
$dataGrid->column('sae_unit_price', 'Giá mua', 'money', true)->addExport();
$dataGrid->column('sae_other_cost', 'CP Khác', 'money')->addExport();
$dataGrid->column('sae_total_ban', 'Tổng mua', 'money', true)->addExport();

$dataGrid->column('sae_payment_status', 'Trạng thái TT', function ($row) {
    $status = $row->payment_status ?? 'Chưa thanh toán';
    $colors = [
        'Chưa thanh toán'     => '#e74c3c',
        'Thanh toán một phần' => '#f39c12',
        'Đã thanh toán'       => '#27ae60',
    ];
    $color = $colors[$status] ?? '#999';
    return '<span style="color:' . $color . ';font-weight:bold">' . htmlspecialchars($status) . '</span>';
})->addExport();

$dataGrid->column('sae_payment_date', 'Ngày TT', function ($row) {
    return $row->payment_date ? date('d/m/Y', strtotime($row->payment_date)) : '';
})->addExport();

$dataGrid->column('', 'Sửa', 'edit|center');

echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render(),
    'date_from'  => $date_from,
    'date_to'    => $date_to,
    'search'     => $search,
])->render();
die;
?>
