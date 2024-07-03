<?

use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\Users\Users;

require_once 'inc_security.php';

$model = new App\Models\Users\Users();

$user_id = getValue('user_id');
$user = $model->findByID($user_id);

if (!$user) {
    exit;
}

$points = OrderCommission::fields('*')->sum('orc_point', 'amount')
    ->inner_join('orders', 'ord_id = orc_order_id AND ord_status_code = \'' . Order::SUCCESS . '\'')
    ->with(['user', 'order'])
    ->where('orc_status_code', OrderCommission::STATUS_SUCCESS)
    ->where('orc_user_id', $user->id)
    // ->where('orc_is_direct', '=', 0)
    ->group_by('ord_id')
    ->order_by('orc_created_at', 'DESC')
    ->pagination(getValue('page', 'int', 'GET', 1), 10)
    ->all();

// dd($points);
$dataGrid = new DataGrid($points, 10000, 'ord_id');
$dataGrid->column('orc_created_at', 'Ngày', 'datetime');
$dataGrid->column('order.code', 'Đơn hàng', 'string');
$dataGrid->column('order.amount', 'Giá trị đơn', 'money');
$dataGrid->column('amount', 'Point', function ($row) {
    return number_format($row->amount);
});


echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;
