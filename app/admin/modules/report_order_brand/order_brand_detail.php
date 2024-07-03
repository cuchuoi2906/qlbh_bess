<?php
use App\Models\Order;


require_once 'inc_security.php';

$user_id = getValue('userId', 'int', 'GET', '', 0);


$items_model = \App\Models\Order::with(['products','productsDetail', 'logs'])
    ->inner_join('users', 'use_id = ord_user_id')
->inner_join('order_products', 'orp_ord_id = ord_id');
$sqlWhere = " use_id IN (" . $user_id . ")";
$sqlWhere .= " AND ord_status_code != '" . \App\Models\Order::CANCEL. "'";
$sqlWhere .= " AND ord_status_code != '" . \App\Models\Order::REFUND. "'";
$range_date = getValue('ord_created_at', 'str', 'GET', '', 3);
if (!$range_date) {
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}
$v_range_date_where  = '';
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $date_type = getValue('date_type', 'int', 'GET', 1);
    $date_field = ' AND ord_created_at';

    $sqlWhere .= $date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
	//$items_model->where($date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}
$items_model->where($sqlWhere);
$querryString  = $items_model->toSelectQueryString();
$result = $items_model->all();
$v_arr_data = [];
$v_arr_data_pro = [];
$brands = App\Models\Categories\Category::where('cat_type', 'BRAND')
    ->all();
$brands = $brands->lists('cat_id', 'cat_name_vn');

foreach($result as $items){
	$productsDetail = $items->productsDetail->toArray();
    foreach($productsDetail as $data){
        $v_arr_data[$data['id']]['pro_code'] = isset($brands[$data['brand_id']]) ? $brands[$data['brand_id']] : 'Thương hiệu khác';
    }
	$products = $items->products->toArray();
    foreach($products as $data1){
        $v_arr_data_pro[] = $data1;
    }
}
$v_so_luong_thuong_hieu = count($v_arr_data);
$tong_tien = 0;
foreach($v_arr_data_pro as $items){
	$tong_tien += ($items['sale_price'] * $items['quantity']);
	$product_id = $items['product_id'];
	$v_arr_data[$product_id]['money'] =  intval($v_arr_data[$product_id]['money']) + ($items['sale_price'] * $items['quantity']);
}
$v_arr_data = array_values($v_arr_data);
// tính tỷ lệ bank
foreach($v_arr_data as $key=>$items){
    $v_arr_data[$key]['rate_brand'] = ($items['money']/$tong_tien)*100;
}
echo $blade->view()->make('order_brand_detail', compact('v_arr_data') + get_defined_vars())->render();