<?php

use App\Models\Users\Users;

require_once 'inc_security.php';

$items_model = \App\Models\Order::with(['products','productsDetail', 'logs'])
    ->inner_join('users', 'use_id = ord_user_id');

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
    $date_field = ' ord_created_at';

    //$sqlWhere .= $date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
	$items_model->where($date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}
$items_model->where('ord_status_code', '!=','REFUND');
$items_model->where('ord_status_code', '!=','CANCEL');
$user_id = getValue('use_id', 'str', 'GET', '', 0);
if($user_id > 0){
	$items_model->where('use_id', $user_id);
}
$user_sale_id = getValue('user_sale_id', 'int', 'GET', '', 0);
if ($user_sale_id > 0){
	$items_model->where('user_sale_id', $user_sale_id);
}

$items_model ->setFields('*
        ,SUM(ord_amount) totalAmount
        ,COUNT(ord_id) totalOrder
		,(SELECT a.use_name FROM users a WHERE a.use_id = users.user_sale_id LIMIT 1) use_name_sale
        '
);
$items_model->group_by('use_id');
$total_money = 0;
if (!getValue('export', 'str') == 'Export') {
    $total_model = clone $items_model;
    $total_money = $total_model->sum('ord_amount')->select();
    $total_money = $total_money->total;

}

$querryString  = $items_model->toSelectQueryString();
//echo $querryString;
$items_model->pagination(getValue('page'), $per_page);
$items= $items_model->all();
$total = $items_model->count();
$dataGrid = new DataGrid($items, $total, 'use_id');
$dataGrid->model = $model;
$dataGrid->deleteLabel = 'hủy';
//Search
$dataGrid->search('ord_created_at', 'Thời gian', 'string', true);
$dataGrid->column('use_id', 'ID', 'number', [], true)->addExport();
$dataGrid->column('use_name', 'Tên', ['string', 'trim'], [])->addExport();
$dataGrid->column('use_name_sale', 'Tên sale phụ trách', ['string', 'trim'], [])->addExport();
$dataGrid->column('user_sale_id', 'Id sale phụ trách',  function ($row) {
    $user_sale_id = intval($row['user_sale_id']) > 0 ? $row['user_sale_id'] : '';
    return $user_sale_id;
}, [],true)->addExport();
$dataGrid->column('totalOrder', 'Tổng số đơn hàng', function ($row) {
    $totalOrder = $row['totalOrder'];
    return number_format($totalOrder);
})->addExport();


$dataGrid->column('totalAmount', 'Giá trị đơn hàng', function ($row) {
    global $view_option;
    $totalAmount1 = $row['totalAmount'];
    return number_format($totalAmount1);
})->addExport();

//$dataGrid->total('totalAmount', $total_money, '');

$dataGrid->column(false, 'Xem chi tiết', function ($row) use ($blade,$range_date) {
    return $blade->view()->make('order_brand_modal', compact('row') + get_defined_vars())->render();

});
        
echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;