<?php

use App\Models\Users\UserAddress;

require_once 'inc_security.php';
checkAddEdit("add");

//Tạo đơn hàng cho người dùng
$ErrorCode = '';
$myform = new generate_form();

//Khai báo biến khi thêm mới
$fs_title = translate("Thêm mới sản phẩm");
$fs_action = getURL();
$fs_errorMsg = "";
$ord_id = getValue('ord_id', 'int', 'GET', 0);
$items_model = \App\Models\Order::with(['productsDetail', 'logs']);
$items_model->where('ord_active', 1);
$items_model->where('ord_id', $ord_id);
$items = $items_model->first();
$cart_products = $items->productsDetail;
$total_product = $cart_products->count();

$proIdList = implode(',',$cart_products->pluck('pro_id')->toArray());

// Chỉ lấy những sản phẩm còn hàng
$sqlWhere_product  = ' 1=1 AND pro_active_inventory = 1 AND pro_active = 1 AND pro_id not in('.$proIdList.')';
$products = \App\Models\Product::where($sqlWhere_product)->all(); 
$products = [0 => 'Chọn sản phẩm'] + $products->lists('pro_id', 'pro_name_vn');


echo $blade->view()->make('add_product', get_defined_vars())->render();