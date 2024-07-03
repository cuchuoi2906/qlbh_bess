<?php
require_once 'inc_security.php';


//$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);


$items_model = \App\Models\OrderProduct::with(['orders','products', 'logs','commissionsproduction', ['commissionsorderpro', function ($model) {
    return $model->where('orc_type', '=', 0);
}]])
    ->inner_join('products', 'pro_id = orp_product_id')
    ->inner_join('orders', 'ord_id = orp_ord_id')
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
        $date_field = 'ord_updated_at';
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
$pro_code = getValue('pro_code', 'str', 'GET', '');
if ($pro_code != '') {
    $items_model->where('pro_code', $pro_code);
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
//var_dump($items->toSelectQueryString());
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
$dataGrid = new DataGrid($items, $total, 'pro_id', $per_page);
$dataGrid->model = $items_model;
$dataGrid->search(['date_type', $array_date_type], 'Tìm kiếm theo', 'selectShow', true);
$dataGrid->column('ord_code', 'Mã đơn', 'string', true, true)->addExport();
$dataGrid->column('ord_created_at', 'Ngày đặt', function ($row) {
    return (new DateTime($row->ord_created_at))->format('H:i:s d/m/Y');
}, true, true)->addExport();;
$dataGrid->column('ord_updated_at', 'Ngày xử lý', function ($row) {
    return (new DateTime($row->ord_updated_at))->format('H:i:s d/m/Y');
}, true, true)->addExport();;
$dataGrid->column('use_name', 'Người đặt', 'string', true)->addExport();
$dataGrid->column('use_phone', 'Sđt đặt', function ($row) {
    return $row->use_phone ?? '';
}, [], true)->addExport();
$dataGrid->column('ord_ship_phone', 'Sđt người nhận', function ($row) {
    return $row->ord_ship_phone ?? '';
}, [])->addExport();
$dataGrid->column('ord_ship_name', 'Tên người nhận', function ($row) {
    return $row->ord_ship_name ?? '';
}, [])->addExport();
$dataGrid->column('ord_ship_address', 'Địa chỉ người nhận', function ($row) {
    $v_address = $row->ord_ship_address;
    if($row->ord_ward_id > 0 && $row->ord_district_id > 0 && $row->ord_province_id > 0){
        $ward = \App\Models\Ward::findByID($row->ord_ward_id);
        $district = \App\Models\District::findByID($row->ord_district_id);
        $province = \App\Models\Province::findByID($row->ord_province_id);
        $v_address .= '-'. $ward->name.'-'.$district->name.'-'.$province->name;
    }
    return $v_address ?? '';
}, [])->addExport();
$search_multi_status = !$status ? ['multi' => true] : false;
$dataGrid->column(['ord_payment_type', ['' => 'Tất cả'] + \App\Models\Order::paymentTypes()], 'Hình thức thanh toán', 'selectShow', true, true)->addExport();
$dataGrid->column(['ord_payment_status', [-1 => 'Tất cả'] + \App\Models\Order::paymentStatus()], 'Trạng thái thanh toán', 'selectShow', true, true)->addExport();
$dataGrid->column(['ord_status_code', \App\Models\Order::$status], 'Trạng thái', 'selectShow', true, $search_multi_status)->addExport();
$dataGrid->column('pro_code', 'Mã sản phẩm', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('pro_name_' . locale(), 'Tên sản phẩm', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('orp_quantity', 'Số lượng', ['string', 'trim'], [])->addExport();
$dataGrid->column(uniqid(), 'Đơn giá', function ($row) {
    $so_luong = (int)$row->orp_quantity;
    $orp_price = $row->orp_price;
    return number_format($orp_price);
})->addExport();
$v_uni_chiet_khau = time();
$dataGrid->column(uniqid(), 'Chiết khấu', function ($row) {
    // Giá chưa sale
    $so_luong = (int)$row->orp_quantity;
    $orp_price = $row->orp_price;
    // Thành tiền
    $orp_sale_price = $row->orp_sale_price;
    $commission = ($so_luong*((int)$orp_price)) - ($so_luong*((int)$orp_sale_price));
    return number_format($commission);
})->addExport();
$dataGrid->column($v_uni_thanh_tien, 'Thành tiền', function ($row) {
    $so_luong = (int)$row->orp_quantity;
    //$pro_price = $row->pro_price;
    //$pro_commission = $row->orp_sale_price;
    $orp_sale_price = $row->orp_sale_price;
    $don_gia = $so_luong*((int)$orp_sale_price);
    return number_format($don_gia);
})->addExport();
$dataGrid->column(uniqid(), 'Hoa hồng theo đơn hàng', function ($row) {
    $commission = 0;
    foreach ($row->commissionsorderpro as $item) {
        $commission += (int)$item->orc_amount;
    }
    return number_format($commission);
})->addExport();
$dataGrid->column('orp_commission_product', 'Hoa hồng theo sản phẩm', function ($row) {
    return number_format($row->orp_commission_product);
})->addExport();
$dataGrid->column('ord_shipping_fee', 'Phí ship shop chịu', function ($row) {
    return number_format($row['ord_shipping_fee']);
})->addExport();
$dataGrid->column('ord_auto_shipping_fee', 'Phí ship khách chịu', function ($row) {
    return number_format($row['ord_auto_shipping_fee']);
})->addExport();
$dataGrid->column(uniqid(), 'Doanh thu', function ($row) {
    $phi_ship_minh_chiu = (int)$row->ord_shipping_fee;
    $phi_ship_khach_chiu = (int)$row->ord_auto_shipping_fee;
    $commission = 0;
    $commission_vat = 0;
    foreach ($row->commissionsorderpro as $item) {
        $commission += (int)$item->orc_amount;
        $commission_vat += (int)$item->orc_vat;
    }
    $total_commission = $commission + $commission_vat;
    $revenue = $row->ord_amount + $phi_ship_khach_chiu - $total_commission - $phi_ship_minh_chiu;
    return number_format($revenue);
})->addExport();
$dataGrid->column('ord_amount', 'Giá trị đơn hàng (VNĐ)', function ($row) {
    return number_format($row['ord_amount']);
}, true)->addExport();
/*$dataGrid->column(uniqid(), 'Hoa hồng theo sản phẩm', function ($row) {
    $commission = 0;
    foreach ($row->commissionsproduction as $item) {
        if($row->orp_product_id == $item->opc_product_id){
            $commission += (int)$item->opc_commission;
            $commission += (int)$item->opc_vat;
        }
    }
    return number_format($commission);
})->addExport();*/

$dataGrid->column(uniqid(), 'Doanh thu sau vận chuyển', function($row) {
    $commission = 0;
    foreach ($row->commissionsorderpro as $item) {
        $commission += (int)$item->orc_amount;
    }
    return number_format($commission);
    $doanhthu = (int)$row['ord_amount']-$commission-$row['ord_shipping_fee']+$row['ord_auto_shipping_fee'];
    return number_format($doanhthu);
}, [])->addExport();

$dataGrid->column(uniqid(), 'Tổng giá trị phải thu khách hàng', function($row) {
    $donhang = $row['ord_amount'];
    $doanhthu = (int)$donhang+$row['ord_auto_shipping_fee'];
    return number_format($doanhthu);
}, [])->addExport();
/*$dataGrid->column(uniqid(), 'Phí vận chuyển', function ($row) {
    $don_gia = $row->ord_shipping_fee + $row->ord_auto_shipping_fee;
    return number_format($don_gia);
})->addExport();*/

$dataGrid->column(uniqid(), 'CK', function ($row) {
    $ord_amount = $row['ord_payment_type'] == 'BANKING' ? number_format($row['ord_amount']) : 0;
    return $ord_amount;
}, true)->addExport();
$dataGrid->column(uniqid(), 'COD', function ($row) {
    $ord_amount = $row['ord_payment_type'] == 'COD' ? number_format($row['ord_amount']) : 0;
    return $ord_amount;
}, true)->addExport();
$dataGrid->column(uniqid(), 'VÍ', function ($row) {
    $ord_amount = $row['ord_payment_type'] == 'WALLET' ? number_format($row['ord_amount']) : 0;
    return $ord_amount;
}, true)->addExport();

// $dataGrid->column('ord_ship_email', 'Email người nhận', 'string', [], false);

/*$dataGrid->column(uniqid(), 'VAT hoa hồng theo đơn hàng', function ($row) {
    $commission_vat = 0;
    foreach ($row->commissionsorderpro as $item) {
        $commission_vat += (int)$item->orc_vat;
    }
    return number_format($commission_vat);
});*/
if ($status == 'SUCCESS') {
    $dataGrid->column('ord_shipping_carrier', 'Đơn vị VC', 'string', [], true);
    $dataGrid->column('ord_shipping_code', 'Mã VC', 'string', [], true);
    $dataGrid->column('ord_shipping_fee', 'Phí VC', 'money', [], true);
}
$dataGrid->column('pro_barcode', 'Barcode', ['string', 'trim'], [], true)->addExport();
$dataGrid->column(uniqid(), 'Ðã thanh toán', function($row) {
    if($row['ord_payment_status'] == \App\Models\Order::PAYMENT_STATUS_NEW){
        return 0;
    }
    return number_format($row['ord_amount']);
}, [])->addExport();
$dataGrid->column(uniqid(), 'Chưa thanh toán', function($row) {
    if($row['ord_payment_status'] == \App\Models\Order::PAYMENT_STATUS_SUCCESS){
        return 0;
    }
    return number_format($row['ord_amount']);
}, [])->addExport();

/*$dataGrid->column(uniqid(), 'Thành tiền', function ($row) {
    $so_luong = (int)$row->orp_quantity;
    $don_gia = (int)$row->pro_price;
    $revenue = $so_luong + $don_gia;
    return number_format($revenue);
});*/
/*$dataGrid->column(uniqid(), 'Hoa hồng theo sản phẩm', function ($row) {
    $don_gia = (int)$row->pro_commission;
    return number_format($don_gia);
});*/

/*$dataGrid->column('ord_auto_shipping_fee', 'Phí ship khách chịu', function ($row) {
    return number_format($row['ord_auto_shipping_fee']);
});
$dataGrid->total('ord_auto_shipping_fee', $total_auto_shipping_fee, 'đ');
*/

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
