<?
require_once 'inc_security.php';

$sqlWhere = "1";

//if ($cat_name) {
//    $sqlWhere .= " AND cat_name_" . locale() . " LIKE '%" . $cat_name . "%'";
//}
//
//if ($type) {
//    $sqlWhere .= " AND cat_type = '" . $type . "'";
//}

$items = \App\Models\Event::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->all();
$total = \App\Models\Event::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'evt_id', true);
$dataGrid->column('evt_id', 'ID', 'number');

$dataGrid->column('evt_name', trans('label.name', 'Tên'), ['string', 'trim'], [], true);
$dataGrid->column('evt_note', trans('label.note', 'Ghi chú'), ['string', 'trim']);
$dataGrid->column('evt_start_time', trans('label.start_date', 'Ngày bắt đầu'), function ($row) {

    return date('d-m-Y', $row->start_time);
});
$dataGrid->column('evt_end_time', trans('label.end_date', 'Ngày kết thúc'), function ($row) {

    return $row->end_time ? date('d-m-Y', $row->end_time) : 'Không kết thúc';
});
$dataGrid->column('evt_direct_commission_ratio', 'Tỷ lệ hoa hồng trực tiếp', 'number');
$dataGrid->column('evt_parent_commission_ratio', 'Tỷ lệ hoa hồng cấp trên', 'number');

$dataGrid->column('evt_active', 'Trạng thái', 'activeDisabled|center');
$dataGrid->column(uniqid(), 'Sửa', 'edit|center');
$dataGrid->column(uniqid(), 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
