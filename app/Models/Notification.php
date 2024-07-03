<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/7/19
 * Time: 01:46
 */

namespace App\Models;


class Notification extends Model
{

    public $table = 'notification';
    public $prefix = 'not';


    public function admin()
    {
        return $this->hasOne(
            __FUNCTION__,
            AdminUser::class,
            'adm_id',
            'not_admin_id'
        );
    }

}