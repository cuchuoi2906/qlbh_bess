<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 01:49
 */

namespace App\Models;


use VatGia\Model\Model;

class OrderProduct extends Model
{

    public $table = 'order_products';

    public $prefix = 'orp';

    public function info()
    {
        return $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'orp_product_id'
        );
    }

    public function order()
    {

        return $this->hasOne(
            __FUNCTION__,
            Order::class,
            'ord_id',
            'orp_ord_id'
        );
    }
    public function commissionsorderpro()
    {
        return $this->hasMany(
            __FUNCTION__,
            OrderCommission::order_by('orc_amount', 'DESC'),
            'orc_order_id',
            'orp_ord_id'
        );
    }
    public function commissionsproduction()
    {
        return $this->hasMany(
            __FUNCTION__,
            OrderProductCommission::order_by('opc_commission', 'DESC'),
            'opc_order_id',
            'orp_ord_id'
        );
    }
}