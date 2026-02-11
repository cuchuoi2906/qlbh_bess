<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/5/20
 * Time: 19:11
 */

require_once 'inc_security.php';

$order_id = getValue('order_id', 'int', 'POST', 0);
$product_id = getValue('product_id', 'int', 'POST', 0);
$quantity = getValue('quantity', 'int', 'POST', 0);

$order = \App\Models\Order::findByID($order_id);
$productOrigin = \App\Models\Product::findByID($product_id);
if (
    $order
    && $order->status_code == \App\Models\Order::NEW
    && (int)$order->payment_status == \App\Models\Order::PAYMENT_STATUS_NEW
) {
    $price = $productOrigin->pro_discount_price;
    if($productOrigin->pricePolicies){
        foreach($productOrigin->pricePolicies as $items){
            if($quantity >= $items->ppp_quantity){
                $price = intval($items->ppp_price);
                break;
            }
        }
    }
    $product = \App\Models\OrderProduct::where('orp_ord_id', $order_id)
        ->where('orp_product_id', $product_id)
        ->first();

    if ($product) {
        
        $old_quantity = $product->quantity;
        $product->quantity = (int)$quantity;
        $product->sale_price = (int)$price;
        $product->update();

        //Tính lại hoa hồng
        \App\Manager\Order\OrderManager::commissions($order->id);

        //Log
        \App\Models\OrderLog::insert([
            'orl_ord_id' => $order_id,
            'orl_old_status_code' => $order->status_code,
            'orl_new_status_code' => $order->status_code,
            'orl_old_payment_status' => $order->payment_status,
            'orl_new_payment_status' => $order->payment_status,
            'orl_updated_by' => $admin_id ?? 0,
            'orl_note' => 'Thay đổi số lượng sản phẩm ' . $product->info->name . ' từ ' . $old_quantity . ' thành ' . $quantity
        ]);

        admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $order->id, 'Đã thay đổi số lượng sản phẩm ' . $product->name . ' của đơn hàng (' . $order->id . ') từ ' . $old_quantity . ' thành ' . $quantity);
    }
}
