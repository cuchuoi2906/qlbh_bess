<?php


require_once 'inc_security.php';


//$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);


$items_model = \App\Models\Order::with(['products', 'logs', ['commissions', function ($model) {
    return $model->where('orc_type', '=', 0);
}]])
    ->inner_join('users', 'use_id = ord_user_id');
if ($status ?? '') {
    $items_model->where('ord_status_code', $status);
} else {
    $ord_status_code = getValue('ord_status_code', 'arr', 'GET', [], 3);
    if ($ord_status_code) {
        $items_model->where('ord_status_code', $ord_status_code);
    }
}
$items_model->where('ord_active', 1);

$range_date = getValue('ord_created_at', 'str', 'GET', '', 3);
if (!$range_date) {
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}

if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $date_type = getValue('date_type', 'int', 'GET', 1);
    if ($date_type == 2) {
        $date_field = 'ord_pending_at';
    } else {
        $date_field = 'ord_created_at';
    }

    $items_model->where($date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

$ord_code = getValue('ord_code', 'str', 'GET', '', 3);
if ($ord_code) {
    $items_model->where('ord_code', $ord_code);
}

$ord_user_id = getValue('ord_user_id', 'int', 'GET', 0);
if ($ord_user_id) {
    $items_model->where('ord_user_id', $ord_user_id);
}

$ord_payment_status = getValue('ord_payment_status', 'int', 'GET', -1);
if ($ord_payment_status >= 0) {
    $items_model->where('ord_payment_status', '=', (int)$ord_payment_status);
}

$ord_shipping_code = getValue('ord_shipping_code', 'str', 'GET', '');
if ($ord_shipping_code) {
    $items_model->where('ord_shipping_code', $ord_shipping_code);
}


$items_model->fields('*, IF(ord_status_code = \'NEW\', 1, 0) AS status_order');
if (getValue('export', 'str') == 'Export') {
    $items = $items_model;
}else{
    $items = $items_model->pagination(getValue('page'), $per_page);
}

if (sorting()) {
    $items_model->order_by(sort_field(), sort_type());
}else{
    $items_model->order_by('ord_updated_at', 'DESC');
}

$items = $items->all();
$total_money = 0;
if (!getValue('export', 'str') == 'Export') {
    $total_model = clone $items_model;
    $total_money = $total_model->sum('ord_amount')->select();
    $total_money = $total_money->total;

    /*Total fee ship*/
    $total_model = clone $items_model;
    $total_shipping_fee = $total_model->sum('ord_shipping_fee')->select();
    $total_shipping_fee = $total_shipping_fee->total;

    /*Total fee ship*/
    $total_model = clone $items_model;
    $total_auto_shipping_fee = $total_model->sum('ord_auto_shipping_fee')->select();
    $total_auto_shipping_fee = $total_auto_shipping_fee->total;


}
$total = $items_model->count();

//dd($items_model);
$dataGrid = new DataGrid($items, $total, 'ord_id', $per_page);
$dataGrid->model = $items_model;

$dataGrid->search(['date_type', $array_date_type], 'Tìm kiếm theo', 'selectShow', true);
$dataGrid->column('ord_code', 'Mã đơn hàng', 'string', true)->addExport();
$dataGrid->column('ord_created_at', 'Ngày đặt', function ($row) {
    return (new DateTime($row->created_at))->format('H:i:s d/m/Y');
}, true, true)->addExport();
$dataGrid->column('ord_pending_at', 'Ngày xử lý', function ($row) {
    if($row->ord_pending_at != ''){
		return (new DateTime($row->ord_pending_at))->format('H:i:s d/m/Y');
	}
	return '';
}, true)->addExport();
$dataGrid->column('ord_shipping_code', 'Mã vận chuyển', 'string', true,true)->addExport();
$dataGrid->column('ord_shipping_carrier', 'Hãng vận chuyển', 'string', true)->addExport();

$dataGrid->column('ord_shipping_fee', 'Tổng phí ship', function ($row) {
    return number_format($row['ord_shipping_fee']);
})->addExport();
$dataGrid->total('ord_shipping_fee', $total_shipping_fee, 'đ');

$dataGrid->column('ord_auto_shipping_fee', 'Phí ship khách chịu', function ($row) {
    return number_format($row['ord_auto_shipping_fee']);
})->addExport();
$dataGrid->total('ord_auto_shipping_fee', $total_auto_shipping_fee, 'đ');

$dataGrid->column('ord_amount', 'Giá trị đơn hàng (VNĐ)', function ($row) {
    return number_format($row['ord_amount']);
}, true)->addExport();

$dataGrid->column(uniqid(), 'Doanh thu thực', function ($row) {
    $phi_ship_minh_chiu = (int)$row->ord_shipping_fee;
    $phi_ship_khach_chiu = (int)$row->ord_auto_shipping_fee;
    $commission = 0;
    $commission_vat = 0;
    foreach ($row->commissions as $item) {
        $commission += (int)$item->orc_amount;
        $commission_vat += (int)$item->orc_vat;
    }
    $total_commission = $commission + $commission_vat;
    $revenue = $row->ord_amount + $phi_ship_khach_chiu - $total_commission - $phi_ship_minh_chiu;
    return number_format($revenue);
})->addExport();

$dataGrid->total('ord_amount', $total_money, 'đ');

$search_multi_status = !$status ? ['multi' => true] : false;
$dataGrid->column(['ord_status_code', \App\Models\Order::$status], 'Trạng thái', 'selectShow', true, $search_multi_status)->addExport();
$dataGrid->column(['ord_payment_status', [-1 => 'Tất cả'] + \App\Models\Order::paymentStatus()], 'Trạng thái thanh toán', 'selectShow', true, true)->addExport();

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
