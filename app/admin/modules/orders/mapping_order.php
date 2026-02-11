<?php

require_once 'inc_security.php';
$ord_id = getValue('ord_id', 'int', 'GET', 0);
$ord_code_mapping = getValue('ord_code_mapping', 'str', 'POST', "");
$orderMapping = \App\Models\Order::where('ord_code',$ord_code_mapping)->where('ord_status_code','NEW')->first();

$order = \App\Models\Order::findByID($ord_id);
if (!$order || !$orderMapping) {
    die;
}
if($order->ord_ship_phone != $orderMapping->ord_ship_phone){
    die;
}
$productsOrder = $order->products->toArray();
$arrProductCheck = [];
foreach($productsOrder as $items){
    $arrProductCheck[$items['product_id']]['id'] = $items['id'];
    $arrProductCheck[$items['product_id']]['quantity'] = $items['quantity'];
}
if(check_array($arrProductCheck)){
    $productsMapping = $orderMapping->products;
    pre($arrProductCheck);
    foreach($productsMapping as $productMap){
        if(isset($arrProductCheck[$productMap->product_id]) && intval($arrProductCheck[$productMap->product_id]['id']) > 0){ // Tăng số lượng sp ở đơn hàng kia lên
            \App\Models\OrderProduct::update([
                'orp_quantity' => intval($arrProductCheck[$productMap->product_id]['quantity'] + $productMap->quantity)
            ],'orp_id = '.$arrProductCheck[$productMap->product_id]['id']);
        }else{
            \App\Models\OrderProduct::update([
                'orp_ord_id' => $ord_id
            ],'orp_id = '.$productMap->id);
        }
    }
    $orderMapping->update([
        'ord_status_code' => 'CANCEL'
    ]);
    // Tinh lai don hang
    \App\Manager\Order\OrderManager::commissions($ord_id);
}