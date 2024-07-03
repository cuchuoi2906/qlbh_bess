<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/9/20
 * Time: 09:59
 */

namespace App\Models\Users;


use App\Models\Model;

class UserChangePhone extends Model
{

    public $table = 'user_change_phone';
    public $prefix = 'ucp';

    public function user()
    {
        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'ucp_user_id'
        );
    }
}