<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 16/10/2018
 * Time: 16:12
 */

namespace AppView\Repository;


interface AgencyInterface
{
    function all();

    function agency_show();

    function get_by_city_district($city_id, $district_id);
}