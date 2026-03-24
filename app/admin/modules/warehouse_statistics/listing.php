<?php
require_once 'inc_security.php';

$fs_title = "Thống kê sản phẩm và doanh thu";

// --- Filters ---
$year   = getValue('year', 'int', 'GET', (int)date('Y'));
$search = getValue('search', 'str', 'GET', '', 3);

// --- Danh sách sản phẩm (category 243, chưa xóa) ---
$_products = \App\Models\Product::where('pro_category_id = 243 AND pro_deleted_at IS NULL')
    ->order_by('pro_name_vn', 'ASC')
    ->select_all();

$product_names = [];
if ($_products) {
    foreach ($_products as $_p) {
        $product_names[] = $_p->name_vn;
    }
}

// Lọc theo tên sản phẩm
if ($search) {
    $product_names = array_values(array_filter($product_names, function ($name) use ($search) {
        return mb_stripos($name, $search) !== false;
    }));
}

// --- Build monthly pivot SQL (tránh lặp code) ---
function buildMonthlyPivotSQL($table, $nameCol, $dateCol, $qtyCol, $amountCol, $year)
{
    $yearSafe = (int)$year;
    $sql = "SELECT {$nameCol} AS product_name,
            COALESCE(SUM({$qtyCol}), 0) AS total_qty,
            COALESCE(SUM({$amountCol}), 0) AS total_amount";

    for ($m = 1; $m <= 12; $m++) {
        $sql .= ", COALESCE(SUM(CASE WHEN MONTH({$dateCol}) = {$m} THEN {$qtyCol} ELSE 0 END), 0) AS qty_m{$m}";
        $sql .= ", COALESCE(SUM(CASE WHEN MONTH({$dateCol}) = {$m} THEN {$amountCol} ELSE 0 END), 0) AS amt_m{$m}";
    }

    $sql .= " FROM {$table}
              WHERE YEAR({$dateCol}) = {$yearSafe}
              GROUP BY {$nameCol}";

    return $sql;
}

// --- Query nhập kho ---
$sql_import = buildMonthlyPivotSQL('warehouse', 'who_product_name', 'who_import_date', 'who_quantity', 'who_total_price', $year);
$import_data = [];
$db = new db_query($sql_import);
if ($db->result) {
    while ($row = mysqli_fetch_object($db->result)) {
        $import_data[$row->product_name] = $row;
    }
}
unset($db);

// --- Query xuất kho ---
$sql_export = buildMonthlyPivotSQL('sales_export', 'sae_product_name', 'sae_export_date', 'sae_quantity_ban', 'sae_total_ban', $year);
$export_data = [];
$db = new db_query($sql_export);
if ($db->result) {
    while ($row = mysqli_fetch_object($db->result)) {
        $export_data[$row->product_name] = $row;
    }
}
unset($db);

// --- Tính toán thống kê cho từng sản phẩm ---
$stats = [];
$totals = [
    'sl_ton' => 0,
    'total_import_qty' => 0,
    'total_export_qty' => 0,
    'loi_lai' => 0,
    'total_import_amount' => 0,
    'total_export_amount' => 0,
];
for ($m = 1; $m <= 12; $m++) {
    $totals["qty_m{$m}"] = 0;
    $totals["amt_m{$m}"] = 0;
}

foreach ($product_names as $name) {
    $imp = $import_data[$name] ?? null;
    $exp = $export_data[$name] ?? null;

    $total_import_qty    = $imp ? (int)$imp->total_qty : 0;
    $total_export_qty    = $exp ? (int)$exp->total_qty : 0;
    $total_import_amount = $imp ? (float)$imp->total_amount : 0;
    $total_export_amount = $exp ? (float)$exp->total_amount : 0;
    $sl_ton  = $total_import_qty - $total_export_qty;
    $loi_lai = $total_export_amount - $total_import_amount;

    $row = [
        'name'                => $name,
        'sl_ton'              => $sl_ton,
        'total_import_qty'    => $total_import_qty,
        'total_export_qty'    => $total_export_qty,
        'loi_lai'             => $loi_lai,
        'total_import_amount' => $total_import_amount,
        'total_export_amount' => $total_export_amount,
    ];

    for ($m = 1; $m <= 12; $m++) {
        $row["qty_m{$m}"] = $exp ? (int)$exp->{"qty_m{$m}"} : 0;
        $row["amt_m{$m}"] = $exp ? (float)$exp->{"amt_m{$m}"} : 0;
    }

    $stats[] = $row;

    // Cộng dồn tổng
    $totals['sl_ton']              += $sl_ton;
    $totals['total_import_qty']    += $total_import_qty;
    $totals['total_export_qty']    += $total_export_qty;
    $totals['loi_lai']             += $loi_lai;
    $totals['total_import_amount'] += $total_import_amount;
    $totals['total_export_amount'] += $total_export_amount;
    for ($m = 1; $m <= 12; $m++) {
        $totals["qty_m{$m}"] += $row["qty_m{$m}"];
        $totals["amt_m{$m}"] += $row["amt_m{$m}"];
    }
}

// Danh sách năm (5 năm gần nhất)
$year_list = range((int)date('Y'), (int)date('Y') - 4);

echo $blade->view()->make('listing', compact(
    'stats', 'totals', 'year', 'search', 'year_list'
))->render();
?>
