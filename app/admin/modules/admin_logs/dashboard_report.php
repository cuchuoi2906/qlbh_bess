<?

require_once 'inc_security.php';

disable_debug_bar();

/**
 * @var VatGia\Model\ModelBase $model
 */
$model = new $model;

$range_date = getValue('created_at', 'str', 'GET', '', 3);
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $model->where("adl_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

$adl_action = getValue('action', 'str', 'GET', '');
if ($adl_action) {
    $model->where('adl_action', $adl_action);
}

$items = $model
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('adl_id', 'DESC')
    ->all();

$total = $model->count();


$dataGrid = new DataGrid($items, $total, $id_field);

$dataGrid->column('admin_user_id', 'Quản trị viên', function ($row) {
    return $row->admin->name;
});
$dataGrid->column('record_title', 'Diễn giải', 'string', [], false);
$dataGrid->column('created_at', 'Thời gian', 'string', [], false);

echo $blade->view()->make('dashboard_report', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
