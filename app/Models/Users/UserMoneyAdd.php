<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-18
 * Time: 11:16
 */

namespace App\Models\Users;


use App\Models\Model;

class UserMoneyAdd extends Model
{

    public $table = 'user_money_add';
    public $prefix = 'uma';

    const TYPE_METHOD_ONLINE = "ONLINE";
    const TYPE_METHOD_ADMIN = "ADMIN";


    public static function types()
    {
        return
            [
                self::TYPE_METHOD_ONLINE => "Qua cổng thanh toán",
                self::TYPE_METHOD_ADMIN => "Cộng tiền trực tiếp qua admin",
            ];
    }

    public function user()
    {
        $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'uma_user_id'
        );
    }

}