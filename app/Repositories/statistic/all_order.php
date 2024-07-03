<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 09/05/2021
 * Time: 09:11
 */


$user = \App\Models\Users\Users::findByID(input('user_id'));

$total = 0;
$money = 0;
$start_date = input('start_date');
$end_date = input('end_date');


if ($user) {
    $model = \App\Models\Order::inner_join('users', 'ord_user_id = use_id')
        ->where('use_all_level', 'LIKE', '%.' . $user->id . '.%')
        ->where('ord_status_code','!=', \App\Models\Order::NEW)
        ->where('ord_status_code','!=', \App\Models\Order::CANCEL);
    if ($start_date) {
        $model->where('ord_created_at', '>=', $start_date . ' 00:00:00');
    }
    if ($end_date) {
        $model->where('ord_created_at', '<=', $end_date . ' 23:59:59');
    }

    $total = $model->count();
    $money = $model->sum('ord_amount', 'total')->select();
    $money = $money->total;
}

return [
    'vars' => [
        'total' => (int)$total,
        'total_display' => number_format($total),
        'money' => (int)$money,
        'money_display' => number_format($money),
    ]
];