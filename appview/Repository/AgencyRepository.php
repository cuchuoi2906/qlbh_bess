<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 16/10/2018
 * Time: 16:18
 */

namespace AppView\Repository;


class AgencyRepository implements AgencyInterface
{

    function all()
    {
        $result = model('agency/all')->load([]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    function agency_show()
    {
        $result = model('agency/agency_show')->load([]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    function get_by_city_district($city_id, $district_id)
    {
        $result = model('agency/get_by_city_district')->load([
            'city_id' => $city_id,
            'district_id' => $district_id,
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }
}