<?

use App\Models\Users\Users;

require_once 'inc_security.php';

$items_model = new \App\Models\OrderProductCommission;

$range_date = getValue('ord_successed_at', 'str', 'GET', '', 3);

if (!$range_date) {
    $start_month = new DateTime('first day of this month');
    $start_day = $start_month->format('d/m/Y');
    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}

if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $items_model->where("ord_successed_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

$pro_barcode = getValue('pro_barcode', 'arr', 'GET', []);
$pro_barcode = array_filter($pro_barcode);
if ($pro_barcode) {
    $items_model->where('pro_barcode', $pro_barcode);
}

$pro_code = getValue('pro_code', 'arr', 'GET', []);
$pro_code = array_filter($pro_code);
if ($pro_code) {
    $items_model->where('pro_code', $pro_code);
}

$pro_id = getValue('pro_id', 'arr', 'GET', []);
$pro_id = array_filter($pro_id);
if ($pro_id) {
    $items_model->where('pro_id', $pro_id);
}

//Lấy tổng số lượng bán hàng theo sản phẩm
$items = $items_model->fields('pro_id, pro_name_vn, pro_code, COUNT(opc_order_id) AS total_count')
    ->sum('opc_commission', 'total_commission')
    ->sum('opc_quantity', 'total_quantity')
    ->inner_join('orders', 'ord_id = opc_order_id')
    ->inner_join('products', 'opc_product_id = pro_id')
    ->where('ord_status_code', \App\Models\Order::SUCCESS)
    ->where('opc_type', '=', 0)
    ->group_by('opc_product_id')
    ->group_by('opc_order_id')
    ->all();

$products = [];

$total_quantity = 0;
$total_money = 0;
$total_commision = 0;

$products = [];

foreach ($items as $item) {
    $item_quantity = $item->total_quantity / $item->total_count;
    if (!isset($products[$item->pro_id])) {
        $products[$item->pro_id] = $item->toArray();
        $products[$item->pro_id]['total_quantity'] = $item_quantity;
//        $products[$item->pro_id]['total_commission'] = $item->total_commision;
    } else {
        $products[$item->pro_id]['total_commission'] += $item->total_commission;
        $products[$item->pro_id]['total_quantity'] += $item_quantity;
    }

    $total_commision += $item->total_commission;
    $total_quantity += $item_quantity;
}

$all_products = \App\Models\Product::all();
$product_barcodes = $all_products->lists('pro_barcode', 'pro_barcode');
$product_codes = $all_products->lists('pro_code', 'pro_code');
$product_codes = array_filter($product_codes);

$product_names = $all_products->lists('pro_id', 'pro_name_vn');


$dataGrid = new DataGrid($products, count($products), 'pro_id');

//Search
$dataGrid->search('ord_successed_at', 'Thời gian', 'string', true);

$dataGrid->column(['pro_code', $product_codes], 'Mã sản phẩm', 'selectShow', [], [
    'multi' => true
]);
$dataGrid->column(['pro_id', $product_names], 'Tên sản phẩm', 'selectShow', [], [
    'multi' => true
]);
$dataGrid->column('total_quantity', 'Tổng số lượng', 'number|right');
$dataGrid->total('total_quantity', $total_quantity);

$dataGrid->column('total_commission', 'Tổng hoa hồng', 'money');
$dataGrid->total('total_commission', $total_commision);

echo $blade->view()->make('products_commission', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
