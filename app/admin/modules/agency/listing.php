<?

use App\Models\Agency;

require_once 'inc_security.php';

$agc_name = getValue('agc_name', 'str', 'GET', '', 3);
$agc_city_id = getValue('agc_city_id', 'int', 'GET', 0);

$sqlWhere = "1";

if ($agc_name) {
    $sqlWhere .= " AND agc_name LIKE '%" . $agc_name . "%'";
}
if ($agc_city_id) {
    $sqlWhere .= " AND agc_city_id = " . $agc_city_id;
}
//
$items = Agency::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('agc_id', 'DESC')
    ->all();
$total = Agency::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'agc_id');

$dataGrid->column('agc_name', 'Tên', ['string', 'trim'], [], true);
$dataGrid->column('agc_phone', 'SĐT', 'string|center');
$dataGrid->column('agc_website', 'Website', 'string|center');
$dataGrid->column(['agc_city_id', $cities], 'Tỉnh thành', 'select', [], true);
$dataGrid->column('agc_show', 'Đại lý lớn', 'active|center');

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
