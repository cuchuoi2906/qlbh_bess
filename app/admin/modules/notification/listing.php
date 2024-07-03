<?php

require_once 'inc_security.php';

$sqlWhere = "1 AND not_admin_id > 0";

//
$items_model = \App\Models\Notification::where($sqlWhere)
    ->with(['admin'])
    ->pagination(getValue('page'), $per_page)
    ->order_by('not_id', 'DESC');
$items_model ->setFields("*,(SELECT group_concat(concat(use_name,' - ',use_id) separator ', ') FROM notification_status INNER JOIN users ON use_id = nts_user_id WHERE not_id = nts_notification_id LIMIT 100) use_name");
$items = $items_model->all();
//pre($items_model->toSelectQueryString());
$total = \App\Models\Notification::where($sqlWhere)->count();
$dataGrid = new DataGrid($items, $total, 'not_id');

$dataGrid->column('not_title', 'Tiêu đề', ['string', 'trim'], [], true);
$dataGrid->column('not_content', 'Nội dung', 'string|center');
$dataGrid->column('use_name', 'Tên người nhận', ['string', 'trim'], []);
$dataGrid->column('not_created_at', 'Thời gian tạo thông báo', function ($row) {
    return (new DateTime($row->not_created_at))->format('H:i:s d/m/Y');
}, true);
$dataGrid->column('not_is_send_all', 'Gửi tất cả?', 'activeDisabled|center');
$dataGrid->column('not_admin_id', 'Người tạo thông báo', function ($row) {
    return $row->admin ? ($row->admin->loginname . ' (' . $row->admin->name . ')') : 'Hệ thống';
});

//$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [ 
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
