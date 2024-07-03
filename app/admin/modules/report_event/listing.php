<?

require_once 'inc_security.php';

$model = new \App\Models\OrderProduct();
$model->fields('*');
$model->mustHave(['order', 'info']);
$model->where('ord_status_code', \App\Models\Order::SUCCESS);

$specialCategories = getValue('categories', 'arr', 'GET', []);
$specialRatio = getValue('price_ratio', 'int', 'GET', 1);
if ($specialCategories) {
    $specialRatio = $specialRatio >= 1 ? $specialRatio : 1;

    $string = '';
    $end_string = '';
    foreach ($specialCategories as $category) {
        $string .= 'IF(orp_product_id = ' . $category . ', orp_quantity*orp_price*' . $specialRatio . ',';
        $end_string .= ')';
    }

    $string1 = $string . '0' . $end_string;

    $model->sum($string1, 'totalSpecial');

    $string = '';
    $end_string = '';
    foreach ($specialCategories as $category) {
        $string .= 'IF(orp_product_id = ' . $category . ', 0,';
        $end_string .= ')';
    }

    $string2 = $string . 'orp_quantity*orp_price' . $end_string;

    $model->sum($string2, 'totalOther');

    $model->sum($string1 . '+' . $string2, 'totalAll');

} else {
    $model->sum('orp_quantity*orp_price', 'totalAll');
}

$range_date = getValue('date_range', 'str', 'GET', '', 3);
if (!$range_date) {
    $range_date = date('01/m/Y') . ' - ' . date('d/m/Y');
}
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $model->where("ord_successed_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}


$model->group_by('ord_user_id');

$model->order_by('totalAll', 'DESC');
//$items = $model->pagination(getValue('page'), 10)->all();
$items = $model->all();

$products = \App\Models\Product::all();

$categories = $products->lists('pro_id', 'pro_name_vn');

$dataGrid = new DataGrid($items, $items->count(), 'ord_user_id', $items->count());

$dataGrid->model = $model;

//Search
$dataGrid->search(['categories', $categories], 'Sản phẩm đặc biệt', 'select', [
    'multi' => true
]);
$dataGrid->search('price_ratio', 'Tỷ lệ', 'number');
$dataGrid->search('date_range', 'Thời gian', 'datetime');

$dataGrid->column('order.user.phone', 'Số điện thoại', 'string', [], [])->addExport();
$dataGrid->column('order.user.email', 'Email', 'string', [], [])->addExport();
$dataGrid->column('order.user.name', 'Tên khách hàng', 'string', [], [])->addExport();

if ($specialCategories) {
    $dataGrid->column('totalSpecial', 'Tổng tiền hàng đặc biệt', 'money')->addExport();
    $dataGrid->column('totalOther', 'Tổng tiền hàng khác', 'money')->addExport();
    $dataGrid->column('totalAll', 'Tổng tiền', 'money')->addExport();
} else {
    $dataGrid->column('totalAll', 'Tổng tiền', 'money')->addExport();
}


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
