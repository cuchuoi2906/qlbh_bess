<?php

require_once 'inc_security.php';

$order_id = getValue('ord_id', 'int', 'GET', 0);

$order = \App\Models\Order::findByID($order_id);
if (
    $order
    && $order->status_code == \App\Models\Order::NEW
) {
    $products = $order->products;
    if ($products) {
        $productArr = [];
        foreach ($products as $product) {
            $productId = $product->product_id;
            $price = getValue('ord_price'.$productId, 'str', 'POST', 0);
            $price = intval(str_replace(",","",$price));
            $productArr[$productId]['price'] =  $price;
            $quantity = getValue('ord_quantity'.$productId, 'int', 'POST', 0);
            $productArr[$productId]['quantity'] =  $quantity;
        }
        // Tính lại đơn hàng
        \App\Manager\Order\OrderManager::commissions($order->id,$productArr);
        
        //Log
        \App\Models\OrderLog::insert([
            'orl_ord_id' => $order_id,
            'orl_old_status_code' => $order->status_code,
            'orl_new_status_code' => $order->status_code,
            'orl_old_payment_status' => $order->payment_status,
            'orl_new_payment_status' => $order->payment_status,
            'orl_updated_by' => $admin_id ?? 0,
            'orl_note' => 'Thay đổi giá sản phẩm ' . $order_id
        ]);

        admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $order->id, 'Đã thay đổi giá sản phẩm ');
    }
}
