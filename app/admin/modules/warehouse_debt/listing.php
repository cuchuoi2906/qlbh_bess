<?php

use App\Models\SalesExport;

require_once 'inc_security.php';

$fs_title = "Quản lý công nợ";

// --- Filters ---
$search         = getValue('search', 'str', 'GET', '', 3);
$who_pro_id_filter = getValue('who_pro_id', 'int', 'GET', 0);
$date_from      = getValue('date_from', 'str', 'GET', '');
$date_to        = getValue('date_to', 'str', 'GET', '');
$payment_status = getValue('payment_status', 'str', 'GET', '');

$sqlWhere = "1";

if ($who_pro_id_filter) {
    $sqlWhere .= " AND sae_pro_id = " . intval($who_pro_id_filter);
} elseif ($search) {
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
if ($payment_status !== '') {
    if ($payment_status === 'Chưa thanh toán') {
        // Bao gồm cả NULL (chưa set) và giá trị "Chưa thanh toán"
        $sqlWhere .= " AND (sae_payment_status IS NULL OR sae_payment_status = 'Chưa thanh toán')";
    } else {
        $safe = addslashes($payment_status);
        $sqlWhere .= " AND sae_payment_status = '{$safe}'";
    }
}

// --- Query danh sách công nợ ---
$items_model = SalesExport::where($sqlWhere)->order_by('sae_export_date', 'DESC');

$items = $items_model->pagination(getValue('page'), $per_page)->all();
$total = SalesExport::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'sae_id', $per_page);
$dataGrid->model = SalesExport::where($sqlWhere)->order_by('sae_export_date', 'DESC');
$dataGrid->dataExport = SalesExport::where($sqlWhere)->order_by('sae_export_date', 'DESC')->select_all();

$dataGrid->column('sae_export_date', 'Ngày mua', function ($row) {
    return $row->export_date ? date('d/m/Y', strtotime($row->export_date)) : '';
}, true)->addExport();

$dataGrid->column('sae_customer_name', 'Khách hàng', ['string', 'trim'], true)->addExport();
$dataGrid->column('sae_customer_phone', 'Số điện thoại', ['string', 'trim'])->addExport();
$dataGrid->column('sae_product_name', 'Sản phẩm', ['string', 'trim'], true)->addExport();
$dataGrid->column('sae_who_id', 'ĐVT', function ($row) use ($packaging_map, $packaging_units) {
    $unit = $packaging_map[(int)($row->who_id ?? 0)] ?? 0;
    return $packaging_units[$unit] ?? '';
})->addExport();
$dataGrid->column('sae_quantity_ban', 'SL SP mua', function ($row) {
    // Dùng dấu phẩy làm phân cách hàng nghìn để Excel parse đúng (1,000 thay vì 1.000)
    return number_format((int)$row->quantity_ban);
}, true)->addExport();
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

// Tính tổng (chỉ khi không export, tránh clone corrupt model)
if (getValue('export', 'str') != 'Export') {
    $total_model = clone $items_model;
    $total_qty = $total_model->sum('sae_quantity_ban')->select();
    $total_qty = $total_qty->total ?? 0;

    $total_model2 = clone $items_model;
    $total_mua = $total_model2->sum('sae_total_ban')->select();
    $total_mua = $total_mua->total ?? 0;

    $dataGrid->total('sae_quantity_ban', $total_qty);
    $dataGrid->total('sae_total_ban', $total_mua, 'đ');
}

$dataGrid->column('', 'Sửa', 'edit|center');

echo $blade->view()->make('listing', [
    'data_table'        => $dataGrid->render(),
    'date_from'         => $date_from,
    'date_to'           => $date_to,
    'search'            => $search,
    'payment_status'    => $payment_status,
    'payment_statuses'  => $payment_statuses,
    'products_list'     => $products_list,
    'who_pro_id_filter' => $who_pro_id_filter,
])->render();
die;
?>
