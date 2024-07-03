<?php

/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/5/16
 * Time: 2:54 PM
 */

namespace App\Models\BannerSlider;


use App\Models\Categories\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\TopRacingCampaign;
use VatGia\Model\Model;

class Banner extends Model {
    public $table = 'banner';
    public $prefix = 'ban';

    public function category() {
        $this->hasOne(
            __FUNCTION__,
            Category::class,
            'cat_id',
            'ban_object_id'
        );
    }

    public function product() {
        $this->hasOne(
            __FUNCTION__,
            Product::class,
            'pro_id',
            'ban_object_id'
        );
    }

    public function news() {
        $this->hasOne(
            __FUNCTION__,
            Post::class,
            'pos_id',
            'ban_object_id'
        );
    }

    public function video() {
        $this->hasOne(
            __FUNCTION__,
            Post::class,
            'pos_id',
            'ban_object_id'
        );
    }

    public function topRacing() {
        $this->hasOne(
            __FUNCTION__,
            TopRacingCampaign::class,
            'trc_id',
            'ban_object_id'
        );
    }
}
