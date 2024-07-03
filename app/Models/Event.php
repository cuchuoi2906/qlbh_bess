<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/15/19
 * Time: 17:27
 */

namespace App\Models;


class Event extends Model
{

    public $table = 'events';
    public $prefix = 'evt';
    public $soft_delete = true;

    public function admin()
    {

        return $this->hasOne(
            __FUNCTION__,
            AdminUser::class,
            'adm_id',
            'evt_admin_id'
        );
    }

}