<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/13/19
 * Time: 04:57
 */

namespace App\Models;


class ProductPricePolicy extends Model
{

    public $table = 'product_price_policies';
    public $prefix = 'ppp';

    public function product()
    {

        return $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'ppp_product_id'
        );
    }

}