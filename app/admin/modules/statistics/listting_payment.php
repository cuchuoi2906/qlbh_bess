<?
require_once 'inc_security.php';
use App\Models\Users\UserMoneyAddRequest;

$module_name = "Thông kê doanh thu";

$fs_table = "user_money_add_request";
$id_field = "umas_id";
$name_field = "ord_ship_name";
$break_page = "{---break---}";

$ord_code = getValue('ord_code', 'str', 'GET', '', 3);
$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);

$times = 15;
$whereTime = 1;

$strDate = "'%Y-%m-%d'";
//$a = db_raw("SELECT * FROM {$fs_table}");
//dump($a);
////$items = \VatGia\Model\Raw::
//dump( UserMoneyAddRequest::db_raw($a));


$items = UserMoneyAddRequest::where('umar_created_at' > $whereTime);
//    ->select(["DATE_FORMAT(`umar_created_at`,{$strDate})"]);
//if ($ord_code) {
//    $items->where('ord_code', $ord_code);
//}
//if ($ord_status_code) {
//    $items->where('ord_status_code', $ord_status_code);
//}
$items->all();
dump($items);
dump($items->dump_sql());
dd();
$items = $items->pagination(getValue('page'), $per_page)
    ->order_by('date', 'DESC')
    ->all();
dump($items);
$total = \App\Models\Order::count();

$dataGrid = new DataGrid($items, $total, 'ord_id');

$dataGrid->column('ord_ship_name', 'Người đặt', 'string');
$dataGrid->column('ord_code', 'Mã đơn', 'string', [], true);
$dataGrid->column(['ord_status_code', \App\Models\Order::$status], 'Trạng thái', 'select', [], true);
$dataGrid->column(['ord_payment_type', \App\Models\Order::paymentTypes()], 'Hình thức thanh toán', ['string', function ($value) {
    return \App\Models\Order::paymentTypes()[$value];
}], [],true);
$dataGrid->column(['ord_payment_status',\App\Models\Order::paymentStatus()], 'Trạng thái thanh toán', ['string',function($value){
    return \App\Models\Order::paymentStatus()[$value];
}], []);
$dataGrid->column('ord_amount', 'Giá trị đơn hàng (VNĐ)', function ($row) {
    return number_format($row['ord_amount']);
});


$dataGrid->column(false, 'Chi tiết đơn', function ($row) use ($blade) {

    $total = 0;
    foreach ($row->products as $product) {
        $total += $product->quantity;
    }

    return $blade->view()->make('order_detail_modal', compact('row', 'total'))->render();

});
//$dataGrid->column(false, 'Sửa trạng thái', function ($row) use ($blade) {
//
//    return $blade->view()->make('order_change_status_modal', compact('row'))->render();
//
//});

//$dataGrid->column(['cat_type', $type_arr], 'Loại', 'select', [], true);

//$dataGrid->column('cat_picture', 'Ảnh đại diện', function ($row) {
//    if ($row['cat_picture']) {
//        return '<img src="http://localhost:5000/upload/' . $row['cat_picture'] . '" />';
//    }
//    return '';
//});

//$dataGrid->column('cat_read', 'Đã đọc?', 'active|center');
//$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
