<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 23:15
 */

require_once 'inc_security.php';

$id = getValue('id', 'int', 'POST', 0);
$note = getValue('note', 'str', 'POST', '');

$payment_request = \App\Models\Users\UserPaymentRequest::findByID($id);
if ($payment_request && !$payment_request->is_paid) {

    $wallet = $payment_request->user->wallet ?? false;
    if ($wallet && $wallet->commission >= $payment_request->money) {
        $wallet->commission -= $payment_request->money;

        $check = sub_money(
            $payment_request->user->id,
            $payment_request->money,
            \App\Models\UserMoneyLog::TYPE_COMMISSION,
            'Rút tiền về tài khoản ngân hàng', 
            $payment_request->bank_id,
            \App\Models\UserMoneyLog::SOURCE_TYPE_PAYMENT
        );

        if ($check) {

            admin_log($admin_id, ADMIN_LOG_ACTION_ACTIVE, $payment_request->id, 'Đã xác nhận rút ' . number_format($payment_request->money) . ' về tài khoản ngân hàng ' . $payment_request->bank_id . ' cho người dùng ' . $payment_request->user->name);

            $payment_request->upr_is_paid = 1;
            $payment_request->upr_paid_time = time();
            $payment_request->upr_admin_accept = $admin_id;
            $payment_request->upr_ip = ip2long($_SERVER['REMOTE_ADDR']);
            $payment_request->note = $note;

            $payment_request->update();
        }
    }
}