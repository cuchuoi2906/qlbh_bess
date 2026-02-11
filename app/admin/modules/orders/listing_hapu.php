<?php


require_once 'inc_security.php';
use App\Models\Setting;

//$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);


$items_model = \App\Models\Order::with(['products', 'logs', ['commissions', function ($model) {
    return $model->where('orc_type', '=', 0);
}]])
    ->inner_join('users', 'use_id = ord_user_id');

$status_process =1;// trạng thái đơn hàng 
$items_model->where('ord_admin_userprice_id', '>', 0);
$status = [\App\Models\Order::NEW];
if ($status ?? '') {
    $items_model->where('ord_status_code','IN', $status);
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
    if ($date_type == 1) {
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
$ord_admin_user_id = getValue('ord_admin_user_id', 'int', 'GET', 0);
if ($ord_admin_user_id) {
    $items_model->where('ord_admin_user_id', $ord_admin_user_id);
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

/*$ord_shipping_code = getValue('ord_shipping_code', 'str', 'GET', '');
if ($ord_shipping_code) {
    $items_model->where('ord_shipping_code', $ord_shipping_code);
}*/


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
/*$dataGrid->column('ord_pending_at', 'Ngày xử lý', function ($row) {
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
 */
$dataGrid->column('use_name', 'Người đặt', 'string', true)->addExport();
$dataGrid->column('ord_use_phone', 'Sđt đặt', function ($row) {
    return $row->user->phone ?? '';
}, [], true)->addExport();

$dataGrid->column(uniqid(), 'User nhặt thuốc', function($row){
    return $row->userAdminHapu->adm_name;
}, []);

$dataGrid->column('ord_ship_phone', 'Sđt người nhận', 'string', [], true)->addExport();
// $dataGrid->column('ord_ship_email', 'Email người nhận', 'string', [], false)->addExport();
$dataGrid->column('ord_code', 'Mã đơn', 'string', true, true)->addExport();

$dataGrid->column('ord_amount', 'Giá trị đơn hàng Bán', function ($row) {
    return number_format($row['ord_amount']);
}, true)->addExport();

$dataGrid->column(uniqid(), 'Giá trị đơn hàng Nhập', function ($row) {
    
    $products = $row->products;
    $total_amount = 0;
    foreach ($products as $item) {
        $total_amount += (int)$item->orp_price_hapu * $item->orp_quantity;
    }
    return number_format($total_amount);
})->addExport();

$dataGrid->column(['ord_status_process', $array_status_hapu], 'Trạng thái nhặt', 'selectShow', true, $search_multi_status)->addExport();

$dataGrid->column('ord_use_note', 'Ghi chú', function ($row) {
    return strip_tags(html_entity_decode($row->user->note)) ?? '';
}, [], true,false)->addExport();

$dataGrid->total('ord_amount', $total_money, 'đ');

if ($status == 'SUCCESS') {
    //$dataGrid->column('ord_shipping_carrier', 'Đơn vị VC', 'string', [], true)->addExport();
    //$dataGrid->column('ord_shipping_code', 'Mã VC', 'string', [], true)->addExport();
    //$dataGrid->column('ord_shipping_fee', 'Phí VC', 'money', [], true)->addExport();
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

$dataGrid->column(false, 'Chi tiết đơn', function ($row) use ($blade,$array_status_hapu, $status_list, $provinces, $is_admin,$status,$arrAdminEditPrice,$arrUserEditPrice) {
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
    $response = model('province/get_district_by_province_id')->load(['province_id' => $row->province_id]);
    $districts = collect_recursive($response['vars']);

    $response = model('province/get_ward_by_district_id')->load(['district_id' => $row->district_id]);
    $wards = collect_recursive($response['vars']);

    $adminUser = \App\Models\AdminUser::where('adm_delete = 0')->all();

    $adminUserPrice = \App\Models\AdminUser::where('adm_delete = 0 AND adm_type = 1')->all();

    return $blade->view()->make('order_detail_change_modal_hapu', compact('row', 'total','adminUser','array_status_hapu') + get_defined_vars())->render();

});

//$dataGrid->column(false, 'Sửa trạng thái', function ($row) use ($blade) {
//
//    return $blade->view()->make('order_change_status_modal', compact('row'))->render();
//
//});

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
