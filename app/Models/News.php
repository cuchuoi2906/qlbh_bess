<?php
/**
 * Created by vatgia-framework.
 * Date: 8/4/2017
 * Time: 12:55 AM
 */

namespace App\Models;


use App\Models\Users\Users;

class News extends \App\Models\Model
{

    public $table = 'posts';
    public $prefix = 'pos';

    public $localeFields = [
        'title',
        'teaser',
        'content'
    ];

    public function author()
    {
        return $this->hasOne('author', Users::class, 'use_id', 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            __FUNCTION__,
            Tags::class
        );
    }
}