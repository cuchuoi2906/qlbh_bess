<?php
/**
 * Created by PhpStorm.
 * User: MyLove
 * Date: 4/23/2019
 * Time: 3:57 PM
 */

namespace App\Models\Users;

use App\Models\AdminUser;
use App\Models\Banks;
use VatGia\Model\Model;

class UserWalletLog extends Model
{
    public $table = 'user_wallet_log';
    public $prefix = 'uwl';

    public function userInfo()
    {
        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'uwl_use_id'
        );
    }

    public function adminInfo()
    {
        return $this->hasOne(
            __FUNCTION__,
            AdminUser::class,
            'adm_id',
            'uwl_admin_id'
        );
    }
}