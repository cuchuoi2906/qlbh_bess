<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/14/19
 * Time: 00:22
 */

$user = \App\Models\Users\Users::findByID(input('user_id'));

$total = 0;
$success = 0;
$start_time = input('start_time');


if ($user) {
    if ($start_time) {
        $total = \App\Models\Order::where('ord_user_id', $user->id)
            ->where('ord_created_at', '>', $start_time)
            ->count();
        $success = \App\Models\Order::where('ord_user_id', $user->id)
            ->where('ord_status_code', \App\Models\Order::SUCCESS)
            ->where('ord_successed_at', '>', $start_time)
            ->count();
    } else {
        $total = \App\Models\Order::where('ord_user_id', $user->id)->count();
        $success = \App\Models\Order::where('ord_user_id', $user->id)->where('ord_status_code', \App\Models\Order::SUCCESS)->count();
    }

}

return [
    'vars' => [
        'total' => $total,
        'success' => $success
    ]
];