<?

use App\Models\Setting;

require_once 'inc_security.php';

$sqlWhere = "1 AND swe_display = 1";
$prefix = getValue('prefix', 'str', 'GET');
if ($prefix) {
    $sqlWhere .= ' AND swe_key LIKE \'' . $prefix . '%\'';
}

$settingsWebsite = Setting::where($sqlWhere)
//    ->order_by('swe_key', 'ASC')
//    ->order_by('swe_value', 'ASC')
        ->order_by(sort_field() ?: 'swe_key', sort_type('ASC'))
    ->pagination(getValue('page', 'int', 'GET', 1), 50)
    ->all();
$total = Setting::where($sqlWhere)->count();

$dataGrid = new DataGrid($settingsWebsite, $total, 'swe_id', 50);
$dataGrid->column('swe_label', 'Tên', ['string', 'trim'], true);
$dataGrid->column('swe_value_' . locale(), 'Giá trị', function ($row) {
    if ($row['swe_type'] == 'image') {
        return '<img src="' . url() . '/upload/settings/' . $row['swe_value_' . locale()] . '" height="100"/>';
    } else if ($row['swe_type'] == 'number') {
        return number_format($row['swe_value_' . locale()]);
    } else {
        return substr( strip_tags(html_entity_decode($row['swe_value_' . locale()])), 0, 100) . '...';
    }

}, true);

//$dataGrid->column('swe_type', 'Kiểu', ['string', 'trim']);
//$dataGrid->column('swe_create_time', 'Ngày tạo', ['string|center', function ($value) {
//    return date('d/m/Y H:i', $value);
//}]);
//$dataGrid->column('swe_update_time', 'Ngày cập nhật', ['string|center', function ($value) {
//    return ($value != null && $value > 0) ? date('d/m/Y H:i', $value) : '--';
//}]);
$dataGrid->column('', 'Sửa', 'edit|center');
//$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
?>