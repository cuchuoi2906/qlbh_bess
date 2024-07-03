<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/5/16
 * Time: 2:54 PM
 */

namespace App\Models\Categories;


use App\Models\Model;
use App\Models\Post;

class Category extends Model
{
    public $table = 'categories';
    public $prefix = 'cat';

    public $soft_delete = true;

    public $localeFields = [
        'name',
        'description',
    ];

    public function posts()
    {
        return $this->hasMany(
            __FUNCTION__,
            Post::class,
            'pos_category_id',
            'cat_id'
        );
    }

    public function childs()
    {
        return $this->hasMany(
            __FUNCTION__,
            static::class,
            'cat_parent_id',
            'cat_id'
        );
    }

}