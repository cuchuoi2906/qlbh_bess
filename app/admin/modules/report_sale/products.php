<?php

require_once 'inc_security.php';

$items_model = new \App\Models\Order();

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
    $items_model->where("ord_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
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
$items = $items_model->fields('pro_id, pro_barcode, pro_name_vn, ord_payment_type, pro_code')
    ->sum('orp_quantity', 'total_product_quantity')
    ->sum('orp_sale_price * orp_quantity', 'total_product_money')
    ->inner_join('order_products', 'ord_id = orp_ord_id')
    ->inner_join('products', 'orp_product_id = pro_id')
    ->where('ord_status_code', 'IN',[\App\Models\Order::PENDING,\App\Models\Order::BEING_TRANSPORTED,\App\Models\Order::RECEIVED,\App\Models\Order::SUCCESS])
    ->group_by('orp_product_id')
    ->group_by('ord_payment_type')
    ->all();

//var_dump($items_model->toSelectQueryString());
$products = [];

$total_quantity = 0;
$total_money = 0;
foreach (\App\Models\Order::paymentTypes() AS $key => $label) {
    ${'total_money_' . $key} = 0;
}

foreach ($items as $item) {
    if (!isset($products[$item->pro_id])) {
        $products[$item->pro_id] = $item;
    } else {

        $products[$item->pro_id]['total_product_quantity'] += $item['total_product_quantity'];
        $products[$item->pro_id]['total_product_money'] += $item['total_product_money'];
    }

    $total_quantity += $item->total_product_quantity;
    $total_money += $item->total_product_money;

    foreach (\App\Models\Order::paymentTypes() AS $key => $label) {
        if ($item->ord_payment_type == $key) {
            ${'total_money_' . $key} += $item['total_product_money'];
            $products[$item->pro_id]['total_product_money_' . $key] += $item['total_product_money'];
        }
    }
}

$all_products = \App\Models\Product::all();
$product_barcodes = $all_products->lists('pro_barcode', 'pro_barcode');
$product_codes = $all_products->lists('pro_code', 'pro_code');
$product_codes = array_filter($product_codes);

$product_names = $all_products->lists('pro_id', 'pro_name_vn');

$dataGrid = new DataGrid($products, count($products), 'pro_id', count($products) ? count($products) : 10);
//Search
$dataGrid->search('ord_successed_at', 'Thời gian', 'string', true);

$dataGrid->column(['pro_code', $product_codes], 'Mã sản phẩm', 'selectShow', [], [
    'multi' => true
]);
$dataGrid->column('', 'Chi tiết', function ($row) use ($range_date) {
    return '<a href="detail_product.php?pro_id=' . $row->pro_id . '&ord_successed_at=' . $range_date . '">Chi tiết</a>';
});
$dataGrid->column(['pro_id', $product_names], 'Tên sản phẩm', 'selectShow', [], [
    'multi' => true
]);
$dataGrid->column('total_product_quantity', 'Tổng số lượng', 'number|right');
$dataGrid->total('total_product_quantity', $total_quantity);
$dataGrid->column('total_product_money', 'Tổng tiền', 'money');
$dataGrid->total('total_product_money', $total_money);

foreach (\App\Models\Order::paymentTypes() as $type => $label) {
    $dataGrid->column('total_product_money_' . $type, $label, 'money');
    $dataGrid->total('total_product_money_' . $type, ${'total_money_' . $type});
}

echo $blade->view()->make('products', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
