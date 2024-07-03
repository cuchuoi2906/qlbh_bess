<?

use App\Models\Users\UserMoneyAddRequest;


require_once 'inc_security.php';

$sqlWhere = "1";
$items = \App\Models\MoneyAddRequestNotify::where('marn_type', '=', $type)
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('marn_id', 'DESC')
    ->all();

$total = \App\Models\MoneyAddRequestNotify::where('marn_type', '=', $type)
    ->count();

$dataGrid = new DataGrid($items, $total, 'marn_id');
//$dataGrid->column('umar_id', 'Request ID', ['string', 'trim'], [], true);
//$dataGrid->column('umar_user_id', 'User ID', ['string', 'trim'], [], true);

$dataGrid->column(false, 'Người dùng', function ($row) {
    return $row->user->mobile . '(' . $row->user->email . ')';
});

$dataGrid->column(['marn_status', $status], 'Trạng thái', 'selectShow', [], []);

if ($type == 1) {
    $dataGrid->column('marn_order_id', 'Đơn hàng', function ($row) use ($blade) {
        if ($row->order ?? false) {
            return $blade->view()->make('order_detail', [
                ] + get_defined_vars())->render();
        }
    });
} else {
    $dataGrid->column('marn_money', 'Số tiền yêu cầu', 'money');
    $dataGrid->column('marn_money_add', 'Số tiền được cộng', function ($row) {
        if ($row->status != 1) {
            return 0;
        }
        return '<div style="text-align: right">' . number_format($row->money_add ? $row->money_add : $row->money) . 'đ' . '</div>';
    });
}

$dataGrid->column('marn_bank_account', 'Tài khoản chuyển', function ($row) use ($blade) {
    return $blade->view()->make('bank_account', [
        ] + get_defined_vars())->render();
});
$dataGrid->column('marn_created_at', 'Ngày tạo', ['string|center', function ($row) {
    return (new DateTime($row->created_at))->format('H:i:s d/m/Y');
}]);
if ($type == 0) {

    $dataGrid->column(false, 'Cộng tiền', function ($row) use ($blade, $status) {
        if ($row->status == 0) {
            return $blade->view()->make('add_money', [
                ] + get_defined_vars())->render();
        }

    });

    $dataGrid->column(false, 'Xử lý', function ($row) use ($blade, $status) {
        if ($row->status == 1) {
            return $status[$row->status];
        }
        return $blade->view()->make('process', [
            ] + get_defined_vars())->render();
    });
}


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
