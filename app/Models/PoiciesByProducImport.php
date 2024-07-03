<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/3/19
 * Time: 08:52
 */

namespace App\Models;


class PoiciesByProducImport extends Model
{

    public $table = 'policies_by_produc_import';
    public $prefix = 'policies';
    
    public static $status = [
        0 => 'Đã hủy',
        1 => 'Chờ import',
        2 => 'Đã import',
        3 => 'Lỗi'
    ];

    public static $orderStatus = [
        0 => 'Đã hủy',
        1 => 'Chờ xử lý',
        2 => 'Đã xử lý',
        3 => 'Lỗi'
    ];

}