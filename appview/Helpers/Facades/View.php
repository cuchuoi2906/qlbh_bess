<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/5/18
 * Time: 16:59
 */

namespace AppView\Helpers\Facades;


use VatGia\Helpers\Facade;

class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }

}