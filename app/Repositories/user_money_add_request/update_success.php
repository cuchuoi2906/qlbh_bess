<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-20
 * Time: 06:34
 *
 */

use App\Models\Users\UserMoneyAddRequest;
use App\Models\Users\UserMoneyAdd;
use App\Models\Order;
use App\Models\OrderLog;

$request = UserMoneyAddRequest::findByID(input('request_id'));
//\AppView\Helpers\Notification::to(1235, 'Tìm yêu cầu thanh toán ' . input('request_id'), 'Tìm yêu cầu thanh toán ' . input('request_id'));
if (!$request) {
//    \AppView\Helpers\Notification::to(1235, 'Yêu cầu thanh toán không tồn tài', 'Yêu cầu thanh toán không tồn tài');
    throw new RuntimeException("Không tìm thấy yêu cầu", 400);
}
if ($request->status == UserMoneyAddRequest::STATUS_SUCCESS) {
//    \AppView\Helpers\Notification::to(1235, 'Yêu cầu thanh toán đã được xử lý', 'Yêu cầu thanh toán đã được xử lý');
//    throw new RuntimeException("Yêu cầu nạp tiền này đã được xử lý " . input('request_id'), 200);
    return [
        'vars' => []
    ];
}

$request->note .= json_encode(input('data'));

switch ($request->type) {
    case UserMoneyAddRequest::TYPE_MONEY_ADD:
//        \AppView\Helpers\Notification::to(1235, 'Trước khi ghi log money add', 'Trước khi ghi log money add');
        UserMoneyAdd::insert([
            'uma_user_id' => $request->user_id,
            'uma_type' => UserMoneyAdd::TYPE_METHOD_ONLINE,
            'uma_amount' => $request->amount,
            'uma_note' => $request->note,
        ]);
//        \AppView\Helpers\Notification::to(1235, 'Sau khi ghi money add', 'Sau khi ghi money add');

        try {
            add_money($request->user_id, $request->amount, \App\Models\UserMoneyLog::TYPE_MONEY_ADD, 'Nạp thành công ' . number_format($request->amount) . 'đ vào tài khoản', \App\Models\UserMoneyLog::SOURCE_BANK);
        } catch (Exception $e) {
//            \AppView\Helpers\Notification::to(1235, $e->getMessage(), $e->getMessage());
        }
        break;
    case UserMoneyAddRequest::TYPE_PAYMENT_ORDER:

//        \AppView\Helpers\Notification::to(1235, 'Xử lý thanh toán hóa đơn', 'Mã đơn' . $request->order->code . '');
        //Ghi Logs order
        $orderLogId = OrderLog::insert([
            'orl_ord_id' => $request->order->id,
            'orl_old_status_code' => $request->order->status_code,
            'orl_new_status_code' => $request->order->status_code,
            'orl_old_payment_status' => $request->order->payment_status,
            'orl_new_payment_status' => Order::PAYMENT_STATUS_SUCCESS,
            'orl_note' => 'Nhận thanh toán từ cổng thanh toán'
        ]);
//        \AppView\Helpers\Notification::to(1235, 'Insert log đơn hàng', '' . $request->order->code . '.');
        //Order
        $request->order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
        $request->order->payment_successed_at = date('Y-m-d H:i:s');
        $request->order->active = 1;
        $request->order->update();

        \App\Models\Users\UserCart::where('usc_user_id', $request->order->user_id)->delete();

//        \AppView\Helpers\Notification::to(1235, 'Update đơn hàng', '' . $request->order->code . '.');

        \AppView\Helpers\Notification::to($request->order->user_id, 'Thanh toán đơn hàng thành công', 'Bạn vừa thanh toán thành công đơn hàng ' . $request->order->code . '. Chúng tôi sẽ xử lý đơn hàng sớm nhất');

        break;
}

//\AppView\Helpers\Notification::to(1235, 'Trước khi update', 'Trước khi update');
$request->status = UserMoneyAddRequest::STATUS_SUCCESS;
$request->update();

//\AppView\Helpers\Notification::to(1235, 'Sau khi update', 'Sau khi update');

return [
    'vars' => []
];