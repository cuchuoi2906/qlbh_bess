<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 30/10/2018
 * Time: 16:16
 */

namespace AppView\Repository;


interface CityDistrictInterface
{
    function allCity();

    function allDistrictByCity($city_id);

}