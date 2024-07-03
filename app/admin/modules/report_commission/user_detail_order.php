<?php

use App\Models\Users\Users;

require_once 'inc_security.php';
$per_page = 1000;

$items_model = new \App\Models\OrderCommission;
//Lấy tổng số lượng bán hàng theo sản phẩm
$items_model->fields('use_name, use_id, use_phone, use_email, ord_code, ord_created_at,orc_amount')
    ->inner_join('orders', 'ord_id = orc_order_id')
    ->inner_join('users', 'use_id = orc_user_id')
    ->where('ord_status_code', \App\Models\Order::SUCCESS)
    ->where('orc_type', '=', 0)
    ->order_by('ord_code','DESC');

$ord_code_new = getValue('ord_code_new', 'str', 'GET', '', 3);
if ($ord_code_new) {
    $items_model->where('ord_code', $ord_code_new);
}
$use_id = getValue('use_id', 'int', 'GET', 0);
if ($use_id) {
    $items_model->where('use_id', $use_id);
}
$range_date = getValue('ord_created_at', 'str', 'GET', '', 3);
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $date_type = getValue('date_type', 'int', 'GET', 1);
    $date_field = 'ord_updated_at';

    $items_model->where($date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}
if(!isset($_GET['export'])){
    $items_model->pagination(getValue('page', 'int', 'GET', 1), $per_page);
}
$items = $items_model->all();
$arr_data = $items->toArray();
$total = $items_model->count();
if(check_array($arr_data)){
    $v_code_order = $arr_data[0]['ord_code'];
    $v_total_commission = 0;
    $v_arr_ord_new= [];
    $i = 0;$k=0;
    foreach($items as $rows){
        if($v_code_order  != $rows->ord_code){
            $v_arr_ord_new[$k]['total_commission'] = $v_total_commission;
            $v_arr_ord_new[$k]['ord_code'] = $v_code_order;
            $k = $i;
            $v_code_order = $rows->ord_code;
            $v_total_commission = $rows->amount;
        }else{
            $v_total_commission += $rows->amount;
            $rows->ord_code = ($i > 0) ? '' : $rows->ord_code;
        }
        
        $i++;
        if(($i+1)>=$items->count()){
            $v_arr_ord_new[$k]['total_commission'] = $v_total_commission;
            $v_arr_ord_new[$k]['ord_code'] = $rows->ord_code;
        }
    }
    $j=0;
    foreach($items as $rows){
        if(isset($v_arr_ord_new[$j])){
            $rows->c_total_commission = $v_arr_ord_new[$j]['total_commission'];
            $rows->ord_code_new = $v_arr_ord_new[$j]['ord_code'];
        }
        $j++;
    }
    // Gán lai giá tri tính toán duoc
}
//pre($arr_data);die;

$dataGrid = new DataGrid($items, $total, 'orc_id', $per_page);
$dataGrid->search(['ord_created_at'], 'Ngày đặt', '', true);
$dataGrid->column('ord_code_new', 'Ðơn hàng', ['string', 'trim'], true, true,true)->addExport();
$dataGrid->column('c_total_commission', 'Tổng hoa hồng', function ($row) {
    return intval($row->c_total_commission) > 0 ? number_format($row->c_total_commission): '';
}, true,'',true,'orc_amount')->addExport();;
$dataGrid->column('use_id', 'ID', ['string', 'trim'], true)->addExport();;
$dataGrid->column('use_name', 'User nhận hoa hồng', ['string', 'trim'], true)->addExport();;
$dataGrid->column('amount', 'Hoa hồng chia', function ($row) {
    return number_format($row->amount);
}, true)->addExport();;
if(isset($_GET['export']) && $_GET['export'] == 'Export'){
    $dataGrid->dataExport = $items;
}
$dataGrid->model = $items_model;

echo $blade->view()->make('user_detail_order', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
