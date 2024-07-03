<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 01:48
 */

namespace App\Models;


use VatGia\Model\Model;

class OrderLog extends Model
{

    public $table = 'order_logs';

    public $prefix = 'orl';

    public function admin()
    {
        return $this->hasOne(
            __FUNCTION__,
            AdminUser::class,
            'adm_id',
            'orl_updated_by'
        );
    }

}