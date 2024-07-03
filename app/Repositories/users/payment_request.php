<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 21:39
 */

$vars = [];

$user = \App\Models\Users\Users::findByID((int)input('user_id'));

if (!$user) {
    throw new Exception('Người dùng không tồn tại', 400);
}

if (!$user->wallet || $user->wallet->commission < input('money')) {
    throw new Exception('Số tiền bạn muốn rút vượt quá số tiền trong ví hoa hồng', 400);
}

$bank = \App\Models\Users\UserBank::findByID((int)input('bank_id'));
if (!$bank) {
    throw new Exception('Tài khoản ngân hàng không tồn tại', 400);
}

$unpaid_request = \App\Models\Users\UserPaymentRequest::where('upr_is_paid = 0')
    ->where('upr_user_id', input('user_id'))
    ->first();

if ($unpaid_request) {
    throw new Exception('Bạn không thể tạo yêu cầu rút tiền khi yêu cầu trước đó chưa được duyệt', 400);
}

$id = \App\Models\Users\UserPaymentRequest::insert([
    'upr_user_id' => (int)input('user_id'),
    'upr_money' => input('money'),
    'upr_bank_id' => (int)input('bank_id'),
    'upr_is_paid' => 0,
    'upr_note' => ''
]);

return [
    'vars' => [
        'id' => $id
    ]
];

