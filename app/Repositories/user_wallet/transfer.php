<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 19:06
 */

$user = \App\Models\Users\Users::findByID(input('user_id'));
if (!$user) {
    throw new RuntimeException('User is not exists', 400);
}

//throw new RuntimeException('Tính năng tạm dừng hoạt động', 400);

if (!($user->wallet ?? false) || (int)$user->wallet->commission < input('money')) {
    throw new RuntimeException('Your commission wallet is not enough', 400);
}

sub_money($user->id, input('money'), \App\Models\UserMoneyLog::TYPE_TRANSFER, 'Chuyển ' . number_format(input('money')) . ' từ ví hoa hồng sang ví tiền nạp', \App\Models\UserMoneyLog::SOURCE_TRANSFER);

$current_wallet = \App\Models\Users\UserWallet::where('usw_user_id', (int)input('user_id'))->find();

$log = \App\Models\Users\UserTransfer::insert([
    'ust_user_id' => (int)input('user_id'),
    'ust_amount' => (int)input('money'),
]);

return [
    'vars' => $log
];