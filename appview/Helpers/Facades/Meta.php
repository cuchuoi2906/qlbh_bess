<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/14/18
 * Time: 00:10
 */

namespace AppView\Helpers\Facades;


use VatGia\Helpers\Facade;

class Meta extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'meta';
    }
}