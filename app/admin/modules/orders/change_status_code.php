<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderCommission;
use App\Workers\TotalTeamPointDayWorker;
use VatGia\Queue\Facade\Queue;

disable_debug_bar();
//check quyền them sua xoa
checkAddEdit("edit");

$orderId = getValue('order_id', 'int', 'POST', 0);
$status = getValue('status', 'str', 'POST', 0);
$note = getValue('note', 'str', 'POST', '');

$order = \App\Models\Order::findByID($orderId);
if (!$order) {
    die('Đơn hàng không tồn tại');
}
if ($order->status_code == \App\Models\Order::SUCCESS) {
    die('Đơn hàng đã thành công. Không thể chuyển trạng thái');
}

if ($status == $order->status_code) {
    die('Không thể chuyển cùng trạng thái đơn hàng');
}

Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
    'user_id' => $order->user->id,
    'order_id' => $order->id
]);

//update trạng thái commission
if ($status == \App\Models\Order::SUCCESS) {

    if($order->payment_status != Order::PAYMENT_STATUS_SUCCESS) {
        throw new RuntimeException('Không thể xác nhận thành công đơn hàng chưa thanh toán', 500);
    }

    try {
        $commiManager = new \App\Manager\OrderCommit\OrderCommissionManager();
        $commiManager->processSuccess($order);

        //Log Order
        //Ghi Logs order
        $orderLogId = OrderLog::insert([
            'orl_ord_id' => $order->id,
            'orl_old_status_code' => $order->status_code,
            'orl_new_status_code' => $status,
            'orl_old_payment_status' => $order->payment_status,
            'orl_new_payment_status' => $order->payment_status,
            'orl_updated_by' => $admin_id,
            'orl_updated_at' => date('Y-m-d H:i:s'),
            'orl_note' => $note
        ]);

        admin_log($admin_id, ADMIN_LOG_ACTION_ACTIVE, $order->id, 'Đã thay đổi trạng thái của đơn hàng (' . $order->id . ') từ ' . $order->status_code . ' thành ' . $status);

        $order->status_code = $status;
        // $order->successed_at = date('Y-m-d H:i:s');
        // if (!$order->payemnt_successed_at) {
        //     $order->payment_successed_at = date('Y-m-d H:i:s');
        // }
        $check = $order->update();

        \AppView\Helpers\Notification::to([$order->user_id], 'Thay đổi trạng thái đơn hàng', 'Đơn hàng ' . $order->code . ' vừa chuyển sang trạng thái ' . \App\Models\Order::$status[$status]);

    } catch (Exception $e) {
        die($e->getMessage());
    }


} else {
    $old_status = $order->status_code;


    //Trừ lại tiền khi đơn chuyển từ hủy sang trạng thái khác
    if ($old_status == \App\Models\Order::CANCEL && $order->payment_type == \App\Models\Order::PAYMENT_TYPE_WALLET) {
        if ($order->user->wallet->charge >= $order->amount) {
            sub_money($order->user->id, $order->amount, \App\Models\UserMoneyLog::TYPE_MONEY_ADD, 'Thanh toán tiền đơn hàng ' . $order->code, $order->id, \App\Models\UserMoneyLog::SOURCE_TYPE_ORDER);
            $order->status_code = $status;
            $check = $order->update();

            //Log Order
            //Ghi Logs order
            $orderLogId = OrderLog::insert([
                'orl_ord_id' => $order->id,
                'orl_old_status_code' => $order->status_code,
                'orl_new_status_code' => $status,
                'orl_old_payment_status' => $order->payment_status,
                'orl_new_payment_status' => $order->payment_status,
                'orl_updated_by' => $admin_id,
                'orl_updated_at' => date('Y-m-d H:i:s'),
                'orl_note' => $note
            ]);
        } else {
            die('Tài khoản không đủ để thanh toán đơn');
        }
    } else {

        $order->status_code = $status;
        if ($status == 'BEING_TRANSPORTED') {
            $ord_shipping_code = getValue('shipping_code', 'str', 'POST', '');
            $ord_shipping_fee = getValue('shipping_fee', 'str', 'POST', '');
            $ord_shipping_car = getValue('shipping_car', 'str', 'POST', '');
            $ord_shipping_number_car = getValue('shipping_number_car', 'str', 'POST', '');
            $ord_shipping_car_start = getValue('shipping_car_start', 'str', 'POST', '');
            $ord_shipping_car_phone = getValue('shipping_car_phone', 'str', 'POST', '');
            $ord_shipping_note = $note;
            
            $ord_shipping_fee = intval(str_replace(",","",$ord_shipping_fee));
            if (!$ord_shipping_car) {
                die('Bạn phải nhập đơn vị vận chuyển');
            }
//            if (!$ord_shipping_fee) {
//                die('Bạn phải nhập phí vận chuyển');
//            }
            if (!$ord_shipping_code) {
                die('Bạn phải nhập mã vận chuyển');
            }
            $order->ord_shipping_code = $ord_shipping_code;
            $order->ord_shipping_fee = $ord_shipping_fee;
            $order->ord_shipping_car = $ord_shipping_car;
            $order->ord_shipping_number_car = $ord_shipping_number_car;
            $order->ord_shipping_car_start = $ord_shipping_car_start;
            $order->ord_shipping_car_phone = $ord_shipping_car_phone;
            $order->ord_shipping_note = $ord_shipping_note;
            $order->ord_shipping_at = date('Y-m-d H:i:s');
        }
        if ($status == 'PENDING') {
            $order->ord_pending_at = date('Y-m-d H:i:s');
        }

        $check = $order->update();

        //Log Order
        //Ghi Logs order
        $orderLogId = OrderLog::insert([
            'orl_ord_id' => $order->id,
            'orl_old_status_code' => $order->status_code,
            'orl_new_status_code' => $status,
            'orl_old_payment_status' => $order->payment_status,
            'orl_new_payment_status' => $order->payment_status,
            'orl_updated_by' => $admin_id,
            'orl_updated_at' => date('Y-m-d H:i:s'),
            'orl_note' => $note
        ]);
        if($old_status != \App\Models\Order::REFUND && $status == \App\Models\Order::REFUND || $old_status != \App\Models\Order::CANCEL && $status == \App\Models\Order::CANCEL){
            OrderCommission::where('orc_order_id', $orderId)->update([
                'orc_point' => 0,
            ]);
        }
        //Hoàn tiền khi hủy đơn hàng thanh toán bằng ví
        if (($old_status != \App\Models\Order::REFUND && $status == \App\Models\Order::REFUND || $old_status != \App\Models\Order::CANCEL && $status == \App\Models\Order::CANCEL) && $order->payment_type == \App\Models\Order::PAYMENT_TYPE_WALLET) {
            try {
                add_money($order->user->id, $order->amount, \App\Models\UserMoneyLog::TYPE_MONEY_ADD, 'Hoàn tiền cho đơn hàng ' . $order->code, $order->id, \App\Models\UserMoneyLog::SOURCE_TYPE_ORDER, 1);
            } catch (Exception $e) {
                die($e->getMessage());
            }

        }
    }


    \AppView\Helpers\Notification::to([$order->user_id], 'Thay đổi trạng thái đơn hàng', 'Đơn hàng ' . $order->code . ' vừa chuyển sang trạng thái ' . \App\Models\Order::$status[$status]);

}
