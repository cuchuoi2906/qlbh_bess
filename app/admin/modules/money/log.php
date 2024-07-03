<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 10/28/20
 * Time: 15:15
 */

require_once 'inc_security.php';

$model = \App\Models\Users\UserWalletLog::with(['adminInfo', 'userInfo']);

$range_date = getValue('created_at', 'str', 'GET', '', 3);
if (!$range_date) {
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $model->where("uwl_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

$model->pagination(getValue('page', 'int', 'GET'), 10);

$model->order_by('uwl_created_at', 'DESC');
$items = $model->all();
$total = $model->count();

$dataGrid = new DataGrid($items, $total, 'uml_id');
$dataGrid->column('created_at', 'Ngày thực hiện', function ($row) {

    return (new DateTime($row->created_at))->format('H:i:s d/m/Y');
}, [], true);
$dataGrid->column('user_id', 'Người dùng', function ($row) {
    return $row->userInfo ? ($row->userInfo->name . '(' . $row->userInfo->id . ')') : '';
});
$dataGrid->column('amount', 'Số tiền', function ($row) {
    return number_format($row->money_new - $row->money_old);
});
$dataGrid->column('note', 'Ghi chú', 'string');
$dataGrid->column('admin_id', 'Admin', function ($row) {
    return $row->adminInfo->loginname;
});


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
