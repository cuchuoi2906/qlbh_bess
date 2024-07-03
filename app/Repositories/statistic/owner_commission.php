<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 09/05/2021
 * Time: 09:28
 */

$total = 0;

$user = \App\Models\Users\Users::findByID(input('user_id'));
if ($user) {

    $start_date = input('start_date');
    $end_date = input('end_date');

    $model = \App\Models\OrderCommission::sum('orc_amount')
        ->inner_join('orders', 'ord_id = orc_order_id AND ord_status_code != \''.\App\Models\Order::CANCEL.'\' AND ord_status_code != \'' . \App\Models\Order::NEW . '\'')
        ->where('ord_user_id', $user->id)
        ->where('orc_user_id', $user->id)
        //Loại hoa hồng có tính tiền
        ->where('orc_type', '=', 0);

    if ($start_date) {
        $model->where('ord_created_at', '>=', $start_date . ' 00:00:00');
    }

    if ($end_date) {
        $model->where('ord_created_at', '<=', $end_date . ' 23:59:59');
    }

    $total = $model->first();
    $total = $total->total;

}

return [
    'vars' => [
        'total' => (int)$total,
        'total_display' => number_format($total)
    ]
];