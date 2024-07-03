<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-18
 * Time: 11:21
 */

namespace App\Models\Users;


use App\Models\Model;
use App\Models\Order;

class UserMoneyAddRequest extends Model
{
    public $table = 'user_money_add_request';
    public $prefix = 'umar';

    const TYPE_PAYMENT_ORDER = "ORDER";
    const TYPE_MONEY_ADD = "MONEY_ADD";

    const STATUS_NEW = 'NEW';
    const STATUS_SUCCESS = 'SUCCESS';

    public static function status()
    {
        return [
            self::STATUS_NEW => "Giao dịch mới",
            self::STATUS_SUCCESS => "Giao dịch thành công",
        ];
    }


    public static function types()
    {
        return [
            self::TYPE_MONEY_ADD => "Giao dịch nạp tiền",
            self::TYPE_PAYMENT_ORDER => "Giao dịch thanh toán hóa đơn",
        ];
    }



    public function user()
    {
        $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'umar_user_id'
        );
    }

    public function order()
    {
        $this->hasOne(
            __FUNCTION__,
            Order::class,
            'ord_id',
            'umar_order_id'
        );
    }
}