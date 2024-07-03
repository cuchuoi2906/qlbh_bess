<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 01/02/2021
 * Time: 10:05
 */

$user_id = input('user_id');
$user = \App\Models\Users\Users::findByID($user_id);
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

$start_time = input('start_time');
$end_time = input('end_time');

$model = \App\Models\OrderCommission::sum('orc_amount')
    ->inner_join('orders', 'ord_id = orc_order_id AND ord_status_code = \'' . \App\Models\Order::SUCCESS . '\'')
    ->where('orc_user_id', $user->id)
    //Hoa hồng của đơn hàng thành công
    ->where('orc_status_code', \App\Models\OrderCommission::STATUS_SUCCESS)
    //Loại hoa hồng có tính tiền
    ->where('orc_type', '=', 0);

$type = (int)input('type');
switch ($type) {
    //Hoa hồng cá nhân
    case 1:
        $model->where('orc_is_owner', 1);
        break;
    //Đại lý trực tiếp
    case 2:
        $model->where('ord_user_id', $user->childs->lists('use_id'));
        break;
    //Toàn hệ thống
    case 0:
    default:
        break;
}

if ($start_time) {
    $model->where('ord_created_at', '>=', $start_time);
}

if ($end_time) {
    $model->where('ord_created_at', '<=', $end_time);
}

$statistic = $model->first();

return [
    'vars' => (int)$statistic->total
];