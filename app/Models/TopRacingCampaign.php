<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 06/05/2021
 * Time: 08:44
 */

namespace App\Models;


class TopRacingCampaign extends Model
{

    public $table = 'top_racing_campaign';
    public $prefix = 'trc';

    const TYPE_TEAM = 'TEAM';
    const TYPE_OWNER = 'OWNER';

    const TYPES = [
        self::TYPE_OWNER => 'Đua top theo cá nhân',
        self::TYPE_TEAM => 'Đua top theo team',
    ];

    public function products()
    {

        return $this->belongsToMany(
            __FUNCTION__,
            Product::class,
            TopRacingCampaignProduct::class,
            'pro_id',
            'id',
            'trcp_product_id',
            'trcp_campaign_id'
        );
    }
}