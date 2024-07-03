<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/8/18
 * Time: 15:37
 */

namespace App\Models;


use App\Models\Categories\Category;

class Product extends Model
{

    public $table = 'products';
    public $prefix = 'pro';

    public $soft_delete = true;

    protected $localeFields = [
        'name',
        'teaser',
        'functions',
        'specifications'
    ];


    public function images()
    {
        return $this->hasMany(
            __FUNCTION__,
            ProductImage::class,
            'pri_product_id',
            'pro_id'
        );
    }

    public function avatar()
    {
        return $this->hasOne(
            __FUNCTION__,
            ProductImage::where('pri_is_avatar = 1'),
            'pri_product_id',
            'pro_id'
        );
    }

    public function category()
    {
        return $this->hasOne(
            __FUNCTION__,
            Category::class,
            'cat_id',
            'pro_category_id'
        );
    }

    /**
     * @return \VatGia\Model\ModelBase
     *
     * Chính sách hoa hồng cho sản phẩm. Chỉ lấy những chính sách đang active + trong thời gian hoạt động
     */
    public function planCommission()
    {
        return $this->hasOne(
            __FUNCTION__,
            CommissionPlan::where('cpl_active = 1 AND (cpl_start_time IS NULL OR cpl_start_time = 0 OR (cpl_start_time <= ' . time() . ' AND cpl_end_time >= ' . time() . '))'),
            'cpl_id',
            'pro_commission_plan_id'
        );
    }

    public function pricePolicies()
    {

        $this->hasMany(
            __FUNCTION__,
            ProductPricePolicy::order_by('ppp_quantity', 'ASC'),
            'ppp_product_id',
            'pro_id'
        );
    }

    public function minPricePolicy()
    {

        $this->hasOne(
            __FUNCTION__,
            ProductPricePolicy::order_by('ppp_quantity', 'ASC'),
            'ppp_product_id',
            'pro_id'
        );
    }

    public function buyed()
    {
        $buyed = false;
        if (!$this->is_trial) {
            return true;
        }
        if (app('auth')->u_id ?? 0) {
            $buyed = OrderProduct::where('orp_product_id', '=', $this->id)
                ->inner_join('orders', 'ord_id = orp_ord_id')
                ->where('ord_user_id', '=', app('auth')->u_id)
                ->where('ord_status_code', Order::SUCCESS)
                ->first();
        }
        return $buyed ? true : false;
    }

    public function liked()
    {
        $liked = false;
        if (app('auth')->u_id ?? 0) {
            $liked = ProductLiked::where('prl_product_id', '=', (int)$this->id)
                ->where('prl_user_id', app('auth')->u_id)
                ->first();
        }
        return $liked ? true : false;
    }

    public function topRacing()
    {

        return $this->belongsToMany(
            __FUNCTION__,
            TopRacingCampaign::class,
            TopRacingCampaignProduct::class,
            'trc_id',
            'pro_id',
            'trcp_campaign_id',
            'trcp_product_id'
        );
    }

}