<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 15/01/2021
 * Time: 10:53
 */

namespace App\Models\Users;


use App\Models\District;
use App\Models\Model;
use App\Models\Province;
use App\Models\Ward;

class UserAddress extends Model
{

    public $table = 'user_address';
    public $prefix = 'usa';

    public $soft_delete = true;

    public function ward()
    {

        return $this->hasOne(
            __FUNCTION__,
            Ward::class,
            'war_id',
            'usa_ward_id'
        );
    }

    public function district()
    {

        return $this->hasOne(
            __FUNCTION__,
            District::class,
            'dis_id',
            'usa_district_id'
        );
    }

    public function province()
    {

        return $this->hasOne(
            __FUNCTION__,
            Province::class,
            'prov_id',
            'usa_province_id'
        );
    }
}