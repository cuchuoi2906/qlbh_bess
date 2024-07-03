<?php

use App\Models\Users\Users;

require_once 'inc_security.php';

$id = getValue('user_id_member', 'int', 'GET', '', 0);

$model = new App\Models\Users\Users();
$sqlWhere = " use_referral_id = $id AND use_active = 1";
$range_date = getValue('ord_created_at', 'str', 'GET', '', 3);
if (!$range_date) {
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}
$v_range_date_where  = '';
$v_range_date_where_order  = '';
$star_date_fillter = '';
$end_date_fillter = '';
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $date_field = ' AND orc_created_at';
    $date_field_ord = ' AND ord_created_at';

    //$sqlWhere .= $date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
    $v_range_date_where = $date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
    $v_range_date_where_order = $date_field_ord . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
	$star_date_fillter = $star_date->format('Y-m-d');
	$end_date_fillter = $end_date->format('Y-m-d');
}
$user_id_list = getValue('use_id', 'str', 'GET', '', 3);
if ($user_id_list) {
    $sqlWhere .= " AND use_id IN (" . $user_id_list . ")";
}
$model->where($sqlWhere);
	
$model->with(['wallet', ['childs', function (Users $model) {
    return $model->withTrash();
}], 'parent'])
    ->order_by('totalAmount', 'DESC')
->inner_join('order_commissions', 'orc_user_id = use_id'.$v_range_date_where);
$model ->setFields('users.*
        ,(SELECT SUM(ord_amount) FROM orders WHERE ord_user_id = use_id AND ord_status_code != \''.\App\Models\Order::CANCEL.'\' && ord_status_code != \''.\App\Models\Order::NEW.'\' '.$v_range_date_where_order.' ) totalAmount
        ,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE 1=1 '.$v_range_date_where_order.' AND ord_status_code != \''.\App\Models\Order::NEW.'\' AND ord_status_code != \''.\App\Models\Order::CANCEL.'\' AND a.use_referral_id = users.use_id ORDER BY ord_amount DESC) amountReferralAll
        ');
$modelSelect= $model->group_by('use_id');
$querryString  = $modelSelect->toSelectQueryString();
$querryStringCount = 'SELECT count(*) As count
            FROM ('.$querryString.') a';

$db_data_querryStringCount = new db_query($querryStringCount);
$row_querryStringCount = $db_data_querryStringCount->fetch();
$total = intval($row_querryStringCount[0]["count"]);
if(!isset($_GET['export']) || $_GET['export'] != 'Export'){
    $items_model = $model->pagination(getValue('page'), $per_page);
}else{
    set_time_limit( 0);
    ini_set("memory_limit","20024M");
    $items_model = $model;
}
$items= $items_model->all();
foreach($items as $rows){
    $use_id = $rows->use_id;
    $users_point = model('statistic/point_group_member')->get([
                'user_id' => $use_id,
                'start_date' => $star_date_fillter,
                'end_date' => $end_date_fillter,
                'user_id_member' => $use_id,
            ]);
    $rows->total_money_member = $users_point['vars']['total_money_member_report'];
    $rows->total_money_member_f1 = $users_point['vars']['total_money_member_f1'];
    $rows->total_point = $users_point['vars']['total_point'];
    $rows->total_point_member = $users_point['vars']['total_point_member'];
    $rows->total_point_f1 = $users_point['vars']['total_point_f1'];
}

$dataGrid = new DataGrid($items, $total, 'use_id');
if(isset($_GET['export']) && $_GET['export'] == 'Export'){
    $dataGrid->dataExport = $items;
}
$dataGrid->model = $model;
$dataGrid->deleteLabel = 'hủy';
$arrAmountReferralMedium  = array();

$dataGrid->search('ord_created_at', 'Thời gian', 'string');
$dataGrid->column('use_id', 'ID', 'number', [])->addExport();
$dataGrid->column('use_name', 'Tên', 'string', [])->addExport();
$dataGrid->column('use_phone', 'Số điện thoại', 'string', [])->addExport();
$dataGrid->column('use_total_point', 'Điểm marketing', function ($row) {
    return number_format($row['use_total_point']);
},[])->addExport();
$dataGrid->column('use_total_money_member', 'Thưởng cá nhân', function ($row) {
    return number_format($row['use_total_money_member']);
},[])->addExport();

$dataGrid->column(uniqid(), 'Doanh số cá nhân', function ($row) {
    return number_format($row['totalAmount']);
},[])->addExport();

echo $blade->view()->make('point_detail_f1', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;