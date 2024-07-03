<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/6/20
 * Time: 18:17
 */

require_once 'inc_security.php';
$ord_id = getValue('ord_id', 'int', 'GET', 0);
$order = \App\Models\Order::findByID($ord_id);
if (!$order) {
    die;
}

$ord_ship_name = getValue('ord_ship_name', 'str', 'POST', $order->ship_name);
$ord_ship_phone = getValue('ord_ship_phone', 'str', 'POST', $order->ord_ship_phone);
$ord_ship_email = getValue('ord_ship_email', 'str', 'POST', $order->ord_ship_email);
$ord_ship_address = getValue('ord_ship_address', 'str', 'POST', $order->ord_ship_address);
$ord_note = getValue('ord_note', 'str', 'POST', $order->ord_note);
$ord_province_id = getValue('ord_province_id', 'int', 'POST', $order->ord_province_id);
$ord_district_id = getValue('ord_district_id', 'int', 'POST', $order->ord_district_id);
$ord_ward_id = getValue('ord_ward_id', 'int', 'POST', $order->ord_ward_id);

$old_value = [
    'ord_ship_name' => 'Người nhận : ' . $order->ord_ship_name,
    'ord_ship_phone' => $order->ord_ship_phone,
    'ord_ship_email' => $order->ord_ship_email,
    'ord_ship_address' => $order->ord_ship_address,
    'ord_note' => $order->ord_note,
    'ord_province_id' => $order->ord_province_id,
    'ord_district_id' => $order->ord_district_id,
    'ord_ward_id' => $order->ord_ward_id
];

$change = $order->update([
    'ord_ship_name' => $ord_ship_name,
    'ord_ship_phone' => $ord_ship_phone,
    'ord_ship_email' => $ord_ship_email,
    'ord_ship_address' => $ord_ship_address,
    'ord_note' => $ord_note,
    'ord_province_id' => $ord_province_id,
    'ord_district_id' => $ord_district_id,
    'ord_ward_id' => $ord_ward_id
]);

//Log
if ($change) {
    \App\Models\OrderLog::insert([
        'orl_ord_id' => $ord_id,
        'orl_old_status_code' => $order->status_code,
        'orl_new_status_code' => $order->status_code,
        'orl_old_payment_status' => $order->payment_status,
        'orl_new_payment_status' => $order->payment_status,
        'orl_updated_by' => $admin_id ?? 0,
        'orl_note' => 'Thay đổi thông tin nhận hàng'
    ]);
}
