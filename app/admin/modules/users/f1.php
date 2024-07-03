<?php

use App\Models\Users\Users;

require_once 'inc_security.php';

$model = new App\Models\Users\Users();
$sqlWhere = "1";

$use_id = getValue('use_id', 'int', 'GET', 0);
if ($use_id) {
    $sqlWhere .= ' AND use_id = ' . $use_id;
}


$allUsers = Users::fields('use_referral_id, COUNT(*) AS total')
    ->where('use_referral_id', '>', '0')
    ->group_by('use_referral_id')
    ->order_by('total', 'DESC');

$range_date = getValue('created_at', 'str', 'GET', '', 3);
if(isset($_GET['export']) && $_GET['export'] == 'Export'){ // Với export thì export full
	$range_date = '';
}
if (!$range_date) {
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $allUsers->where("use_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

$allUsers = $allUsers->all();

$allUsers = $allUsers->lists('use_referral_id', 'total');
//dd($allUsers);

$itemsModel = $model->where($sqlWhere)->with(['wallet', ['childs', function (Users $model) {
    return $model->withTrash();
}], 'parent'])
    ->pagination(getValue('page'), $per_page)
    ->order_by('total_direct', 'DESC');

$str = 'case  ';
foreach ($allUsers as $id => $total) {
    $str .= 'when use_id = ' . $id . ' then ' . $total . ' ';
}

$str .= ' end AS total_direct';

$itemsModel->fields('*, ' . $str);

$items = $itemsModel->all();

$total = $model->where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'use_id');
$dataGrid->model = $model;
$dataGrid->deleteLabel = 'hủy';
$dataGrid->column('use_id', 'ID', 'number', [], false)->addExport();
$dataGrid->column('created_at', 'Ngày đăng ký', 'datetime', [], true)->addExport();
$dataGrid->column('use_name', 'Tên', ['string', 'trim'], [], false)->addExport();
//$dataGrid->column(['use_gender', [0 => 'Female', 1 => 'Male']], 'Giới tính', 'select');
$dataGrid->column('use_email', 'Email', 'string', [], false)->addExport();
$dataGrid->column('use_phone', 'Số điện thoại', 'string', [], false)->addExport();

$dataGrid->column('total_direct', 'Tổng số F1', 'number')->addExport();

//$dataGrid->search('use_deleted_at', 'Đã hủy?', 'active');
$dataGrid->column('', 'Trạng thái hủy', 'softDelete|center');
$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Hủy', 'delete|center');
$dataGrid->column('', 'Phục hồi', 'restore|center');

if (
    //false &&
    $is_admin ?? false) {
    $dataGrid->column(uniqid(), 'Token', function ($row) {
        return \Firebase\JWT\JWT::encode([
            'user_id' => $row->id
        ], config('app.jwt_key'), 'HS256');
    });
}

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
