<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 09:49
 */

namespace App\Models;


class District extends Model
{

    public $table = 'district';
    public $prefix = 'dis';

    public function wards()
    {

        return $this->hasMany(
            __FUNCTION__,
            Ward::class,
            'war_district_id',
            'dis_id'
        );
    }

}