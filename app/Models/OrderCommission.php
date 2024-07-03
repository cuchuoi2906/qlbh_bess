<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-04
 * Time: 16:29
 */

namespace App\Models;

use App\Models\Users\Users;
use VatGia\Model\Model;

class OrderCommission extends Model
{
    public $table = 'order_commissions';
    public $prefix = 'orc';


    CONST STATUS_NEW = 'new';
    CONST STATUS_SUCCESS = 'success';

    public static function statuses()
    {
        return [
            self::STATUS_NEW => 'Chưa cộng hoa hồng',
            self::STATUS_SUCCESS => 'Đã cộng hoa hồng',
        ];
    }

    public function order()
    {
        $this->hasOne(
            __FUNCTION__,
            Order::class,
            'ord_id',
            'orc_order_id'
        );
    }

    public function products()
    {

        return $this->hasMany(
            __FUNCTION__,
            OrderProduct::class,
            'orp_ord_id',
            'orc_order_id'
        );
    }

    public function user()
    {
        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'orc_user_id'
        );
    }


}