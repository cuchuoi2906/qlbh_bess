<?

use App\Models\Users\Users;

require_once 'inc_security.php';

$items_model = new \App\Models\OrderProductCommission;

$range_date = getValue('ord_successed_at', 'str', 'GET', '', 3);
if (!$range_date) {
    $start_month = new DateTime('first day of this month');
    $start_day = $start_month->format('d/m/Y');
    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}

if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $items_model->where("ord_successed_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

$use_name = getValue('use_name', 'arr', 'GET', []);
$use_name = array_filter($use_name);
if ($use_name) {
    $items_model->where('use_name', $use_name);
}

$use_id = getValue('use_id', 'arr', 'GET', []);
$use_id = array_filter($use_id);
if ($use_id) {
    $items_model->where('use_id', $use_id);
}

$use_refer_name = getValue('use_refer_name', 'arr', 'GET', []);
$use_refer_name = array_filter($use_refer_name);
if ($use_refer_name) {
    $refers = Users::where('use_name', $use_refer_name)->all();
    $refers = $refers->lists('use_id');
    $refers = Users::where('use_referral_id', $refers)->all();
    $refers = $refers->lists('use_id');
    if ($refers) {
        $items_model->where('use_id', $refers);
    }
}

$use_refer_id = getValue('use_refer_id', 'arr', 'GET', []);
$use_refer_id = array_filter($use_refer_id);
if ($use_refer_id) {
    $refers = Users::where('use_referral_id', $use_refer_id)->all();
    $refers = $refers->lists('use_id');
    if ($refers) {
        $items_model->where('use_id', $refers);
    }
}

//Lấy tổng số lượng bán hàng theo sản phẩm
$items = $items_model->fields('use_name, use_id, use_phone, use_email')
    ->sum('opc_commission', 'total_commission')
    ->sum('opc_vat', 'total_vat')
    ->inner_join('orders', 'ord_id = opc_order_id')
    ->inner_join('users', 'use_id = opc_user_id')
    ->where('ord_status_code', \App\Models\Order::SUCCESS)
    ->where('opc_type', '=', 0)
    ->group_by('opc_user_id')
    ->all();

$products = [];

//$total_quantity = 0;
$total_money = 0;
$total_commision = 0;
$total_vat = 0;

$products = [];

foreach ($items as $item) {
    $total_commision += $item->total_commission;
    $total_vat += $item->total_vat;

}

$users = Users::all();
$users_name = $users->lists('use_name', 'use_name');
$user_ids = $users->lists('use_id', 'use_id');

$dataGrid = new DataGrid($items, $items->count(), 'pro_id', $items->count() ? $items->count() : 10);

//Search
$dataGrid->search('ord_successed_at', 'Thời gian', 'string', true);

$dataGrid->search(['use_refer_name', $users_name], 'Tên đại lý giới thiệu', 'select', [
    'multi' => true
]);
$dataGrid->search(['use_refer_id', $user_ids], 'Mã đại lý giới thiệu', 'select', [
    'multi' => true
]);

$dataGrid->column(['use_name', $users_name], 'Tên đại lý', 'selectShow', [], [
    'multi' => true
]);
$dataGrid->column(['use_id', $user_ids], 'Mã giới thiệu', 'selectShow', [], [
    'multi' => true
]);
$dataGrid->column('use_phone', 'SĐT', 'string');
$dataGrid->column('use_email', 'Email', 'string');

$dataGrid->column('total_commission', 'Tổng hoa hồng', 'money');
$dataGrid->column('total_vat', 'Tổng VAT', 'money');
$dataGrid->total('total_commission', $total_commision);
$dataGrid->total('total_vat', $total_vat);

echo $blade->view()->make('products_commission', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
