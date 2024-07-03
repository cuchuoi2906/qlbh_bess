<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 12/8/19
 * Time: 20:51
 */

namespace App\Models;


class OrderProductCommission extends Model
{

    public $table = 'order_product_commission';
    public $prefix = 'opc';

    public function product()
    {

        return $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'opc_product_id'
        );
    }
}