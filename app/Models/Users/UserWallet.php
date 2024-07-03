<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 18:48
 */

namespace App\Models\Users;


use App\Models\Model;

class UserWallet extends Model
{

    public $table = 'user_wallet';
    public $prefix = 'usw';

    public function userInfo()
    {
        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'usw_user_id'
        );
    }

}