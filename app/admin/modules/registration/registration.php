<?php
use App\Models\Registration;

require_once 'inc_security.php';

$model = new App\Models\Registration();
$sqlWhere = "1";
$phone = getValue('re_phone', 'str', 'GET', '', 3);
if ($phone) {
    $sqlWhere .= " AND re_phone = " . $phone;
}
$model->where($sqlWhere);

$model->pagination(getValue('page'), $per_page)
    ->order_by('re_id', 'DESC');
$items= $model->all();
$total = $model->count();

$dataGrid = new DataGrid($items, $total, 're_id');

$dataGrid->model = $model;

$dataGrid->deleteLabel = 'hủy';

$dataGrid->column('re_id', 'ID', 'number', [], false)->addExport();
$dataGrid->column('re_name', 'Họ và tên', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('re_phone', 'Số điện thoại', ['string', 'trim'], [], true)->addExport();
$dataGrid->column('re_email', 'Email', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('re_address', 'Địa chỉ', ['string', 'trim'], [], false)->addExport();
$dataGrid->column('re_comment', 'Thắc mắc', ['string', 'trim'], [], false)->addExport();

echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;