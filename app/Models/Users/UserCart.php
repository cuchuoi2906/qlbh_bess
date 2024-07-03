<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 12:42
 */

namespace App\Models\Users;


use App\Models\Model;
use App\Models\Product;

/**
 * Class UserCart
 * @package App\Models\Users
 *
 */
class UserCart extends Model
{

    public $table = 'user_cart';
    public $prefix = 'usc';

    public function product()
    {
        $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'usc_product_id'
        );
    }

}