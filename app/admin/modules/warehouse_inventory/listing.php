<?php

use App\Models\Warehouse;

require_once 'inc_security.php';

$fs_title = "Quản lý tồn kho";

// --- Filters ---
$search    = getValue('search', 'str', 'GET', '', 3);
$date_from = getValue('date_from', 'str', 'GET', '');
$date_to   = getValue('date_to', 'str', 'GET', '');

$sqlWhere = "1";

if ($search) {
    $searchSafe = addslashes($search);
    $sqlWhere .= " AND (who_product_name LIKE '%{$searchSafe}%' OR who_supplier_name LIKE '%{$searchSafe}%')";
}
if ($date_from) {
    $d = DateTime::createFromFormat('d/m/Y', $date_from);
    if ($d) $sqlWhere .= " AND who_import_date >= '" . $d->format('Y-m-d') . "'";
}
if ($date_to) {
    $d = DateTime::createFromFormat('d/m/Y', $date_to);
    if ($d) $sqlWhere .= " AND who_import_date <= '" . $d->format('Y-m-d') . "'";
}

// --- Tính SL tồn thực tế per product: tổng nhập - tổng xuất ---
$inventory_map = [];
$sql_inv = "SELECT
        COALESCE(i.pname, e.pname) AS product_name,
        COALESCE(i.total_imported, 0) AS total_imported,
        COALESCE(e.total_exported, 0) AS total_exported
    FROM
        (SELECT who_product_name AS pname, SUM(who_quantity) AS total_imported
         FROM warehouse GROUP BY who_product_name) i
    LEFT JOIN
        (SELECT sae_product_name AS pname, SUM(sae_quantity_ban) AS total_exported
         FROM sales_export GROUP BY sae_product_name) e
    ON i.pname = e.pname

    UNION

    SELECT
        e2.pname, COALESCE(i2.total_imported, 0), e2.total_exported
    FROM
        (SELECT sae_product_name AS pname, SUM(sae_quantity_ban) AS total_exported
         FROM sales_export GROUP BY sae_product_name) e2
    LEFT JOIN
        (SELECT who_product_name AS pname, SUM(who_quantity) AS total_imported
         FROM warehouse GROUP BY who_product_name) i2
    ON e2.pname = i2.pname
    WHERE i2.pname IS NULL";

$db = new db_query($sql_inv);
if ($db->result) {
    while ($row = mysqli_fetch_object($db->result)) {
        $inventory_map[$row->product_name] = (int)$row->total_imported - (int)$row->total_exported;
    }
}
unset($db);

// --- Query danh sách nhập kho ---
$items = Warehouse::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('who_import_date', 'DESC')
    ->all();

$total = Warehouse::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'who_id', $per_page);

$dataGrid->column('who_import_date', 'Ngày nhập', function ($row) {
    return $row->import_date ? date('d/m/Y', strtotime($row->import_date)) : '';
}, true)->addExport();

$dataGrid->column('who_supplier_name', 'NCC', ['string', 'trim'], true)->addExport();
$dataGrid->column('who_product_name', 'Sản phẩm', ['string', 'trim'], true)->addExport();

$dataGrid->column('who_packaging_unit', 'ĐVT', function ($row) use ($packaging_units) {
    return $packaging_units[(int)($row->packaging_unit ?? 0)] ?? '';
})->addExport();

$dataGrid->column('who_quantity', 'SL Tồn thực tế', function ($row) use ($inventory_map) {
    $name = $row->product_name;
    $val = $inventory_map[$name] ?? 0;
    $color = $val < 0 ? 'red' : ($val > 0 ? 'green' : '');
    return '<span style="color:' . $color . ';font-weight:bold">' . number_format($val) . '</span>';
})->addExport();

$dataGrid->column('who_unit_price', 'Giá nhập', 'money', true)->addExport();
$dataGrid->column('who_total_price', 'Tổng nhập', 'money', true)->addExport();
$dataGrid->column('who_lot_number', 'Số lô SX/LOT', ['string', 'trim'])->addExport();

$dataGrid->column('who_mfg_date', 'Ngày SX(MFG)', function ($row) {
    return $row->mfg_date ? date('d/m/Y', strtotime($row->mfg_date)) : '';
})->addExport();

$dataGrid->column('who_exp_date', 'Hạn dùng EXP', function ($row) {
    return $row->exp_date ? date('d/m/Y', strtotime($row->exp_date)) : '';
})->addExport();

echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render(),
    'date_from'  => $date_from,
    'date_to'    => $date_to,
    'search'     => $search,
])->render();
die;
?>
