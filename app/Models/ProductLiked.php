<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 01/02/2021
 * Time: 11:25
 */

namespace App\Models;


use App\Models\Users\Users;

class ProductLiked extends Model
{

    public $table = 'product_liked';
    public $prefix = 'prl';

    public function product()
    {

        return $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'prl_product_id'
        );
    }

    public function user()
    {

        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'prl_user_id'
        );
    }
}