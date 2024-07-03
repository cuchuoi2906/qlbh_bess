<?php
use App\Models\Users\Users;

require_once 'inc_security.php';

$sqlWhere = "1";
$sqlWhereMoneyLog = "uml_type ='commission'";
$range_date = getValue('created_at', 'str', 'GET', '', 3);
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    
    $sqlWhereMoneyLog .= " AND uml_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
    $sqlWhere .= " AND uwl_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
}
$use_id = getValue('use_id', 'int', 'GET', 0);
if ($use_id) {
    $sqlWhereMoneyLog .= ' AND uml_user_id = ' . $use_id;
    $sqlWhere .= ' AND uwl_use_id = ' . $use_id;
}

$WalletLog = App\Models\Users\UserWalletLog::inner_join('users', 'use_id = uwl_use_id')
    ->where($sqlWhere);
$WalletLog->fields('use_id,use_name,uwl_money_add as money_add,uwl_money_reduction as money_reduction,uwl_note as note,uwl_created_at as created_at');

$MoneyLog = App\Models\Users\UserMoneyLog::inner_join('users', 'use_id = uml_user_id')
        ->where($sqlWhereMoneyLog);
$MoneyLog->fields('use_id,use_name,uml_amount  as money_add, 0 as money_reduction,uml_note as note,uml_created_at as created_at');

$page = (getValue('page') >= 1) ? (int)getValue('page') : 1;
$start = (int)(($page - 1) * $per_page);
$items = $MoneyLog->union_all($WalletLog,'created_at DESC',$start,$per_page);
$total = $MoneyLog->union_count($WalletLog);

$total_money_add = $MoneyLog->union_sum('money_add',$WalletLog);
$total_reduction = $MoneyLog->union_sum('money_reduction',$WalletLog);

$dataGrid = new DataGrid($items, $total, 'use_id', $per_page);
$dataGrid->dataExport = $MoneyLog->union_all($WalletLog,'created_at DESC',$start,$total);

$dataGrid->column('use_id', 'User ID', 'number', true, true)->addExport();
$dataGrid->column('use_name', 'Tên user', 'string', true, [])->addExport();
$dataGrid->column('money_add', 'Số tiền thêm vào', function ($row) {
    return number_format($row['money_add']);
}, true, [])->addExport();
$dataGrid->column('money_reduction', 'Số tiền giảm đi', function ($row) {
    return number_format($row['money_reduction']);
}, true, [])->addExport();
$dataGrid->column('note', 'Ghi chú', 'string', true, [])->addExport();
$dataGrid->column('created_at', 'Thời gian', 'string', true, true)->addExport();

$dataGrid->total('money_add', $total_money_add, 'đ');
$dataGrid->total('money_reduction', $total_reduction, 'đ');

echo $blade->view()->make('user_accumulation', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;

