<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 06/05/2021
 * Time: 08:44
 */

namespace App\Models;


class TopRacingCampaignProduct extends Model
{

    public $table = 'top_racing_campaign_product';
    public $prefix = 'trcp';

    public function product()
    {

        return $this->hasOne(
            __FUNCTION__,
            Product::class,
            'id',
            'product_id'
        );
    }
}