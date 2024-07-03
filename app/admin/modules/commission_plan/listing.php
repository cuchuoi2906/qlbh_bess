<?

require_once 'inc_security.php';

//$agc_name = getValue('agc_name', 'str', 'GET', '', 3);
//$agc_city_id = getValue('agc_city_id', 'int', 'GET', 0);

$sqlWhere = "1";
//
$items = \App\Models\CommissionPlan::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('cpl_id', 'DESC')
    ->all();
$total = \App\Models\CommissionPlan::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, $id_field);

$dataGrid->column('cpl_plan_name', 'Tên', ['string', 'trim'], [], true);
$dataGrid->column('cpl_commission_percent', '% hoa hồng', 'string|center');

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
