<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/11/20
 * Time: 14:58
 */


use App\Models\Users\Users;

require_once 'inc_security.php';

$user_id = getValue('user_id');

$sqlWhere = "1";

$use_id = getValue('uml_use_id', 'int', 'GET', 0);
if ($use_id) {
    $sqlWhere .= ' AND uml_user_id = ' . (int)$use_id;
}

$uml_log_type = getValue('log_type', 'int', 'GET', -1);
if ($uml_log_type >= 0) {
    $sqlWhere .= ' AND uml_log_type = ' . (int)$uml_log_type;
}


$range_date = getValue('uml_created_at', 'str', 'GET', '', 3);

if (!$range_date) {
//    $start_month = new DateTime('first day of this month');
//    $start_day = $start_month->format('d/m/Y');
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}

if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $sqlWhere .= " AND uml_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . '\'';
}

$itemsModel = \App\Models\UserMoneyLog::where('uml_user_id', $user_id)->where($sqlWhere);

if(sorting())
{
    $itemsModel->order_by(sort_field(), sort_type());
}else{
    $itemsModel->order_by('uml_created_at', 'DESC');
}

$items = $itemsModel ->all();

$total = \App\Models\UserMoneyLog::where('uml_user_id', $user_id)->where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'uml_id', $total);
//$dataGrid->column('use_id', 'ID', 'number', [], true);
$dataGrid->column('uml_created_at', 'Ngày', ['string', 'trim'], [], true);
$dataGrid->column('uml_note', 'Nội dung', 'string');
$dataGrid->column('uml_amount', 'Số tiền', 'money', true);

$dataGrid->search('user_id', 'Đại lý', 'number');

$dataGrid->column(['log_type', [-1 => 'Tất cả', 0 => 'Tiền', 1 => 'Bán hàng']], 'Loại', 'selectShow', [], true);

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
