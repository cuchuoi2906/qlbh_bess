<?php


require_once 'inc_security.php';
use App\Models\Setting;

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
        $date_field = 'ord_created_at';
    } else {
        $date_field = 'ord_pending_at';
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

$ord_payment_type = getValue('ord_payment_type', 'str', 'GET', '');
if ($ord_payment_type) {
    $items_model->where('ord_payment_type', $ord_payment_type);
}

$ord_ship_email = getValue('ord_ship_email', 'str', 'GET', '');
if ($ord_ship_email) {
    $items_model->where('ord_ship_email', $ord_ship_email);
}
$ord_ship_phone = getValue('ord_ship_phone', 'str', 'GET', '');
if ($ord_ship_phone) {
    $items_model->where('ord_ship_phone', $ord_ship_phone);
}
$ord_use_phone = getValue('ord_use_phone', 'str', 'GET', '');
if ($ord_use_phone) {
    $items_model->where('use_phone', $ord_use_phone);
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

$provinces = \App\Models\Province::all();
//dd($items_model);
$dataGrid = new DataGrid($items, $total, 'ord_id', $per_page);
$dataGrid->model = $items_model;

$dataGrid->search(['date_type', $array_date_type], 'Tìm kiếm theo', 'selectShow', true);
$dataGrid->column('ord_created_at', 'Ngày đặt', function ($row) {
    return (new DateTime($row->created_at))->format('H:i:s d/m/Y');
}, true, true)->addExport();
$dataGrid->column('ord_pending_at', 'Ngày xử lý', function ($row) {
    if($row->ord_pending_at != ''){
		return (new DateTime($row->ord_pending_at))->format('H:i:s d/m/Y');
	}
	return '';
}, true)->addExport();
$dataGrid->column('ord_shipping_at', 'Ngày xuất kho', function ($row) {
    if($row->ord_shipping_at != ''){
        return (new DateTime($row->ord_shipping_at))->format('H:i:s d/m/Y');
    }
    return '';
}, true)->addExport();
$dataGrid->column('use_name', 'Người đặt', 'string', true)->addExport();
$dataGrid->column('ord_use_phone', 'Sđt đặt', function ($row) {
    return $row->user->phone ?? '';
}, [], true)->addExport();
$dataGrid->column('ord_ship_phone', 'Sđt người nhận', 'string', [], true)->addExport();
// $dataGrid->column('ord_ship_email', 'Email người nhận', 'string', [], false)->addExport();
$dataGrid->column('ord_code', 'Mã đơn', 'string', true, true)->addExport();

$search_multi_status = !$status ? ['multi' => true] : false;
$dataGrid->column(['ord_status_code', \App\Models\Order::$status], 'Trạng thái', 'selectShow', true, $search_multi_status)->addExport();
$dataGrid->column(['ord_payment_type', ['' => 'Tất cả'] + \App\Models\Order::paymentTypes()], 'Hình thức thanh toán', 'selectShow', true, true)->addExport();
$dataGrid->column(['ord_payment_status', [-1 => 'Tất cả'] + \App\Models\Order::paymentStatus()], 'Trạng thái thanh toán', 'selectShow', true, true)->addExport();
$dataGrid->column('ord_amount', 'Giá trị đơn hàng (VNĐ)', function ($row) {
    return number_format($row['ord_amount']);
}, true)->addExport();

$dataGrid->column('ord_shipping_code', 'Mã vận chuyển', 'string', true,true)->addExport();
$dataGrid->column('ord_shipping_fee', 'Phí ship shop chịu', function ($row) {
    return number_format($row['ord_shipping_fee']);
})->addExport();
$dataGrid->total('ord_shipping_fee', $total_shipping_fee, 'đ');

$dataGrid->column('ord_auto_shipping_fee', 'Phí ship khách chịu', function ($row) {
    return number_format($row['ord_auto_shipping_fee']);
})->addExport();
$dataGrid->total('ord_auto_shipping_fee', $total_auto_shipping_fee, 'đ');

$dataGrid->column(uniqid(), 'Hoa hồng', function ($row) {
    $commission = 0;
    foreach ($row->commissions as $item) {
        $commission += (int)$item->orc_amount;
    }
    return number_format($commission);
})->addExport();
$dataGrid->column(uniqid(), 'VAT hoa hồng', function ($row) {
    $commission_vat = 0;
    foreach ($row->commissions as $item) {
        $commission_vat += (int)$item->orc_vat;
    }
    return number_format($commission_vat);
})->addExport();

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

if ($status == 'SUCCESS') {
    $dataGrid->column('ord_shipping_carrier', 'Đơn vị VC', 'string', [], true)->addExport();
    $dataGrid->column('ord_shipping_code', 'Mã VC', 'string', [], true)->addExport();
    $dataGrid->column('ord_shipping_fee', 'Phí VC', 'money', [], true)->addExport();
}

$dataGrid->column(uniqid(), 'Ghi chú cuối', function($row){
    $log_last = $row->logs->count() ? $row->logs->last() : '';
    if ($log_last) {
        return $log_last->admin ? ( '(' . $row->logs->last()->admin->name . ')' . $row->logs->last()->note) : '';
    }
});
$arrAdminEditPrice = [];
$arrUserEditPrice = [];
if($status == 'NEW'){
    $configAdminEditPrice = Setting::where('swe_key = "user_id_admin"')->select();
    $arrAdminEditPrice  = explode(',', $configAdminEditPrice->swe_value_vn);
    
    $configUserEditPrice = Setting::where('swe_key = "user_order_id"')->select();
    $arrUserEditPrice  = explode(',', $configUserEditPrice->swe_value_vn);
}

$dataGrid->column(false, 'Chi tiết đơn', function ($row) use ($blade, $status_list, $provinces, $is_admin,$status,$arrAdminEditPrice,$arrUserEditPrice) {
    $total = 0;
    foreach ($row->products as $product) {
        $total += $product->quantity;
    }
    $user_id = intval($_SESSION['user_id']);
    $ord_user_id = intval($row->ord_user_id);
    $flagEditPrice = false;
    if(in_array($user_id,$arrAdminEditPrice) && in_array($ord_user_id,$arrUserEditPrice)){
        $flagEditPrice = true;
    }
    if ($row->status_code == \App\Models\Order::NEW && $row->payment_status == \App\Models\Order::PAYMENT_STATUS_NEW) {
        $response = model('province/get_district_by_province_id')->load(['province_id' => $row->province_id]);
        $districts = collect_recursive($response['vars']);

        $response = model('province/get_ward_by_district_id')->load(['district_id' => $row->district_id]);
        $wards = collect_recursive($response['vars']);

        return $blade->view()->make('order_detail_change_modal', compact('row', 'total') + get_defined_vars())->render();
    }
    return $blade->view()->make('order_detail_modal', compact('row', 'total') + get_defined_vars())->render();

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
