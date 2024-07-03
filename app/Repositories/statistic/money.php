<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/23/19
 * Time: 02:19
 */


$total = 0;
$success = 0;

$user = \App\Models\Users\Users::findByID(input('user_id'));
if ($user) {
//    echo \App\Models\Order::sum('ord_amount')
//        ->where('ord_status_code', \App\Models\Order::SUCCESS)
//        ->where('ord_user_id', $user->id)
//        ->where('ord_active', 1)
//        ->toSelectQueryString();
//    die;
    $money_success = \App\Models\Order::sum('ord_amount', 'total_money')
        ->where('ord_status_code', \App\Models\Order::SUCCESS)
        ->where('ord_user_id', $user->id)
        ->where('ord_active', 1)
        ->first();
    $success = $money_success->total_money;
    $money = \App\Models\Order::sum('ord_amount', 'total_money')
        ->where('ord_user_id', $user->id)
        ->where('ord_active', 1)
        ->first();
    $total = $money->total_money;

}

return [
    'vars' => [
        'success' => (int)$success,
        'total' => (int)$total
    ]
];