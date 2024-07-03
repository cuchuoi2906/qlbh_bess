<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 10:15
 */

namespace AppView\Controllers\Api;


class ProvinceController extends ApiController
{

    public function getProvinces()
    {

        $data = model('province/index')->load([]);

        return $data['vars'];
    }

    public function getDistricts()
    {

        $data = model('province/get_district_by_province_id')->load([
            'province_id' => (int)getValue('province_id')
        ]);

        return $data['vars'];
    }

    public function getWards()
    {

        $data = model('province/get_ward_by_district_id')->load([
            'district_id' => (int)getValue('district_id')
        ]);

        return $data['vars'];
    }

}