<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 1/15/20
 * Time: 05:19
 */

namespace App\Models;


use App\Models\Users\Users;

class MoneyAddRequestNotify extends Model
{

    public $table = 'money_add_request_notify';
    public $prefix = 'marn';
    public $soft_delete = true;

    public static $status = [
        -1 => 'Đã hủy',
        0 => 'Chờ nạp tiền',
        1 => 'Đã nạp tiền'
    ];

    public static $orderStatus = [
        -1 => 'Đã hủy',
        0 => 'Chờ xử lý',
        1 => 'Đã xử lý'
    ];

    public function user()
    {

        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'marn_user_id'
        );
    }

    public function order()
    {
        return $this->hasOne(
            __FUNCTION__,
            Order::class,
            'ord_id',
            'order_id'
        );
    }
}