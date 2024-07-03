<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 21:56
 */

namespace App\Models\Users;


use App\Models\AdminUser;
use App\Models\Model;

class UserPaymentRequest extends Model
{

    public $table = 'user_payment_request';
    public $prefix = 'upr';

    public function bank()
    {
        $this->hasOne(
            __FUNCTION__,
            UserBank::class,
            'usb_id',
            'upr_bank_id'
        );
    }

    public function user()
    {
        $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'upr_user_id'
        );
    }

    public function admin()
    {
        $this->hasOne(
            __FUNCTION__,
            AdminUser::class,
            'adm_id',
            'upr_admin_accept'
        );
    }


}