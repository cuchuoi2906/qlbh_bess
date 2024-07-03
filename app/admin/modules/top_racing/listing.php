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

//echo strtotime('2020-01-01');die;

$items = \App\Models\TopRacingCampaign::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->all();
$total = \App\Models\TopRacingCampaign::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'id', 10);
$dataGrid->column('id', 'ID', 'number');

$dataGrid->column('title', trans('label.name', 'Tên'), ['string', 'trim'], [], true);
//$dataGrid->column('evt_note', trans('label.note', 'Ghi chú'), ['string', 'trim']);
$dataGrid->column('start', trans('label.start_date', 'Ngày bắt đầu'), function ($row) {

    return date('d/m/Y', $row->start);
});
$dataGrid->column('end', trans('label.end_date', 'Ngày kết thúc'), function ($row) {

    return $row->end ? date('d/m/Y', $row->end) : 'Không kết thúc';
});
//$dataGrid->column('evt_direct_commission_ratio', 'Tỷ lệ hoa hồng trực tiếp', 'number');
//$dataGrid->column('evt_parent_commission_ratio', 'Tỷ lệ hoa hồng cấp trên', 'number');

$dataGrid->column(uniqid(), 'Danh sách sản phẩm', function ($row) {

    if ($row->all_product) {
        return 'Tất cả sản phẩm';
    }

    $str = '';
    foreach ($row->products as $product) {
        $str .= $product->name . '(' . $product->id . ')</br>';
    }

    return $str;

});

$dataGrid->column('trc_active', 'Trạng thái', 'active|center');
$dataGrid->column(['trc_type', \App\Models\TopRacingCampaign::TYPES], 'Kiểu đua top', 'selectShow');
$dataGrid->column(uniqid(), 'Sửa', 'edit|center');
$dataGrid->column(uniqid(), 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
