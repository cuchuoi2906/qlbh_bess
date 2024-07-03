<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 09:34
 */

namespace App\Models;


class Province extends Model
{

    public $table = 'province';
    public $prefix = 'prov';

    public function districts()
    {

        return $this->hasMany(
            __FUNCTION__,
            District::class,
            'dis_province_id',
            'prov_id'
        );
    }


}