<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/8/18
 * Time: 09:26
 */

namespace App\Models;


use App\Models\Categories\Category;

class Post extends Model
{

    public $table = 'posts';
    public $prefix = 'pos';

    public $soft_delete = true;

    protected $localeFields = [
        'title',
        'teaser',
        'content'
    ];

    public function category()
    {
        return $this->hasOne(
            __FUNCTION__,
            Category::class,
            'cat_id',
            'pos_category_id'
        );
    }

    public function product()
    {
        return $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'pos_product_id'
        );
    }
}