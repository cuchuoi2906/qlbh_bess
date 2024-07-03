<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/5/16
 * Time: 2:54 PM
 */

namespace App\Models;


use App\Models\Model;

class CategoryNews extends Model
{
    public $table = 'category_news';
    public $prefix = 'cat';

    public $localeFields = [
        'name',
        'description',
    ];

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