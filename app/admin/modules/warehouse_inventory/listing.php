<?php

use App\Models\Warehouse;

require_once 'inc_security.php';

$fs_title = "Quản lý tồn kho";

// --- Filters ---
$search    = getValue('search', 'str', 'GET', '', 3);
$who_pro_id_filter = getValue('who_pro_id', 'int', 'GET', 0);
$date_from = getValue('date_from', 'str', 'GET', '');
$date_to   = getValue('date_to', 'str', 'GET', '');

$sqlWhere = "1";

if ($who_pro_id_filter) {
    $sqlWhere .= " AND who_pro_id = " . intval($who_pro_id_filter);
} elseif ($search) {
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

// --- Tính SL tồn thực tế per who_id: số lượng nhập - tổng đã xuất theo who_id ---
$inventory_map = [];
$sql_inv = "SELECT w.who_id,
                   w.who_quantity AS total_imported,
                   COALESCE(SUM(s.sae_quantity_ban), 0) AS total_exported
            FROM warehouse w
            LEFT JOIN sales_export s ON s.sae_who_id = w.who_id
            GROUP BY w.who_id";

$db = new db_query($sql_inv);
if ($db->result) {
    while ($row = mysqli_fetch_object($db->result)) {
        $inventory_map[(int)$row->who_id] = (int)$row->total_imported - (int)$row->total_exported;
    }
}
unset($db);

// --- Lọc bỏ các who_id có tồn = 0 ---
$exclude_ids = [];
foreach ($inventory_map as $wid => $ton) {
    if ($ton == 0) {
        $exclude_ids[] = $wid;
    }
}
if (!empty($exclude_ids)) {
    $sqlWhere .= " AND who_id NOT IN (" . implode(',', $exclude_ids) . ")";
}

// --- Query danh sách nhập kho ---
$items_model = Warehouse::where($sqlWhere)->order_by('who_import_date', 'DESC');

$items = $items_model->pagination(getValue('page'), $per_page)->all();
$total = Warehouse::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'who_id', $per_page);
$dataGrid->model = Warehouse::where($sqlWhere)->order_by('who_import_date', 'DESC');
$dataGrid->dataExport = Warehouse::where($sqlWhere)->order_by('who_import_date', 'DESC')->select_all();

$dataGrid->column('who_import_date', 'Ngày nhập', function ($row) {
    return $row->import_date ? date('d/m/Y', strtotime($row->import_date)) : '';
}, true)->addExport();

$dataGrid->column('who_supplier_name', 'NCC', ['string', 'trim'], true)->addExport();
$dataGrid->column('who_product_name', 'Sản phẩm', ['string', 'trim'], true)->addExport();

$dataGrid->column('who_packaging_unit', 'ĐVT', function ($row) use ($packaging_units) {
    return $packaging_units[(int)($row->packaging_unit ?? 0)] ?? '';
})->addExport();

$dataGrid->column('who_quantity', 'SL Tồn thực tế', function ($row) use ($inventory_map) {
    $val   = $inventory_map[(int)$row->id] ?? 0;
    $color = $val < 0 ? 'red' : ($val > 0 ? 'green' : '');
    return '<span style="color:' . $color . ';font-weight:bold">' . number_format($val) . '</span>';
})->addExport();

$dataGrid->column('who_unit_price', 'Giá nhập', 'money', true)->addExport();
$dataGrid->column('who_other_cost', 'CP Khác', 'money', [], false)->addExport();
$dataGrid->column('who_total_price', 'Tổng nhập', function ($row) use ($inventory_map) {
    $ton = $inventory_map[(int)$row->id] ?? 0;
    $total = ($ton * $row->unit_price) + $row->who_other_cost;
    return number_format($total, 0, ',', '.');
}, true)->addExport();
$dataGrid->column('who_lot_number', 'Số lô SX/LOT', ['string', 'trim'])->addExport();

$dataGrid->column('who_mfg_date', 'Ngày SX(MFG)', function ($row) {
    return $row->mfg_date ? date('d/m/Y', strtotime($row->mfg_date)) : '';
})->addExport();

$dataGrid->column('who_exp_date', 'Hạn dùng EXP', function ($row) {
    return $row->exp_date ? date('d/m/Y', strtotime($row->exp_date)) : '';
})->addExport();

// Tính tổng (chỉ khi không export, tránh corrupt model)
if (getValue('export', 'str') != 'Export') {
    $total_ton = 0;
    $total_nhap = 0;
    $all_filtered = Warehouse::where($sqlWhere)->select_all();
    if ($all_filtered) {
        foreach ($all_filtered as $_r) {
            $ton = $inventory_map[(int)$_r->id] ?? 0;
            $total_ton += $ton;
            $total_nhap += ($ton * $_r->unit_price) + $_r->other_cost;
        }
    }

    $dataGrid->total('who_quantity', $total_ton);
    $dataGrid->total('who_total_price', $total_nhap, 'đ');
}

echo $blade->view()->make('listing', [
    'data_table'       => $dataGrid->render(),
    'date_from'        => $date_from,
    'date_to'          => $date_to,
    'search'           => $search,
    'products_list'    => $products_list,
    'who_pro_id_filter' => $who_pro_id_filter,
])->render();
die;
?>
