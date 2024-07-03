<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/3/19
 * Time: 08:52
 */

namespace App\Models;


class Moneyloadbatch extends Model
{

    public $table = 'money_load_batch';
    public $prefix = 'money';
    
    public static $status = [
        0 => 'Đã hủy',
        1 => 'Chờ nạp tiền',
        2 => 'Đã nạp tiền',
        3 => 'Lỗi'
    ];

    public static $orderStatus = [
        0 => 'Đã hủy',
        1 => 'Chờ xử lý',
        2 => 'Đã xử lý',
        3 => 'Lỗi'
    ];

}