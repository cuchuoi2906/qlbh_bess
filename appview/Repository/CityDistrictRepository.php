<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 30/10/2018
 * Time: 16:17
 */

namespace AppView\Repository;


class CityDistrictRepository implements CityDistrictInterface
{
    /**
     * @return \VatGia\Helpers\Collection
     */
    public function allCity()
    {
        $result = model('cities/all_city')->load([]);

        return collect_recursive($result['vars']);
    }

    /**
     * @param $city_id
     * @return \VatGia\Helpers\Collection
     */
    public function allDistrictByCity($city_id)
    {
        $result = model('cities/all_district_by_city')->load([
            'city_id' => (int)$city_id,
        ]);

        return collect_recursive($result['vars']);
    }

}