<?php

use App\Models\Users\UserAddress;

require_once 'inc_security.php';
checkAddEdit("add");

//Tạo đơn hàng cho người dùng
$ErrorCode = '';
$myform = new generate_form();

//Khai báo biến khi thêm mới
$add = "add.php?type=" . getValue('type', 'str');
$fs_title = translate("Thêm đơn hàng mới");
$fs_action = getURL();
$fs_errorMsg = "";
$ord_user_id = getValue('ord_user_id', 'int', 'GET', 0);
$user_referral_id = getValue('user_referral_id', 'int', 'GET', 0);
$addresses = [];
if ($ord_user_id) {
    $user = App\Models\Users\Users::findByID($ord_user_id);
    $ord_ship_name = $user->name;
    $ord_ship_address = $user->address;
    $ord_ship_phone = $user->phone;
    $ord_ship_email = $user->email;

    //Lấy danh sách sản phẩm
    $result = model('user_cart/index')->load([
        'user_id' => $ord_user_id,
        'admin' => 1
    ]);

    if ($result['vars']['meta'] ?? false) {
        $meta = $result['vars']['meta'];
        //        dd($meta);
        unset($result['vars']['meta']);
        $cart_products = collect_recursive($result['vars']);
    }

    if (getValue('action', 'str', 'POST') == 'execute') {
        try {
            $result = model('order/post_order_v2')->load([
                // 'name' => getValue('ord_ship_name', 'str', 'POST'),
                // 'phone' => getValue('ord_ship_phone', 'str', 'POST'),
                'email' => getValue('ord_ship_email', 'str', 'POST'),
                'address_id' => getValue('ord_address_id', 'str', 'POST'),
                'note' => getValue('ord_ship_note', 'str', 'POST'),
                'user_id' => $ord_user_id,
                'payment_type' => getValue('ord_payment_type', 'str', 'POST'),
                'discount_type' => getValue('ord_commission_type', 'str', 'POST'),
                'admin' => 1,
                'admin_id' => $admin_id
            ]);

            admin_log($admin_id, ADMIN_LOG_ACTION_ADD, $result['vars']['id'] ?? 0, 'Đã thêm mới đơn hàng (' . ($result['vars']['id'] ?? 0) . ') cho người dùng ' . $user->name . '(' . $user->loginname . ')');
            \AppView\Helpers\Facades\FlashMessage::success('Tạo đơn thành công', 'listing.php?status=NEW');
        } catch (Exception $e) {
            $fs_errorMsg = $e->getMessage();
        }
    }

    $addressesModel = UserAddress::where('usa_user_id', $ord_user_id)->all();
    foreach ($addressesModel as $address) {
        $addresses[$address->id] = $address->name . '-' . $address->address . '-' . $address->ward->name . '-' . $address->district->name . '-' . $address->province->name;
    }
}
$provinces = \App\Models\Province::all();
$users = [];
$referral_user = [];
if($user_referral_id > 0){
    $sqlWhere  = ' 1= 1 AND use_active = 1 AND use_referral_id = '.$user_referral_id;
    $usersModel = \App\Models\Users\Users::where($sqlWhere)->all();
    foreach ($usersModel as $items) {
        $users[$items->id] = $items->id.' - '.$items->name . ' - ' . $items->phone;
    }
    $referral_user_model = \App\Models\Users\Users::where('use_active', 1)->all();
    foreach ($referral_user_model as $items) {
        $referral_user[$items->id] = $items->id.' - '.$items->name . ' - ' . $items->phone;
    }
    $referral_user[0] = 'Chọn người giới thiệu';
}else{
    $usersModel = \App\Models\Users\Users::where('use_active', 1)->all();
    foreach ($usersModel as $items) {
        $users[$items->id] = $items->id.' - '.$items->name . ' - ' . $items->phone;
    }
    $referral_user = $users;
    //$referral_user = $referral_user->lists('use_id', 'use_name');
    $referral_user[0] = 'Chọn người giới thiệu';
}
$users_info = $usersModel;
//$users = $users->lists('use_id', 'use_name');
$users = [0 => 'Chọn người dùng'] + $users;
// Chỉ lấy những sản phẩm còn hàng
$sqlWhere_product  = ' 1=1 AND pro_active_inventory = 1 AND pro_active = 1';
$products = \App\Models\Product::where($sqlWhere_product)->all(); 
$products = [0 => 'Chọn sản phẩm'] + $products->lists('pro_id', 'pro_name_vn');


echo $blade->view()->make('add', get_defined_vars())->render();
