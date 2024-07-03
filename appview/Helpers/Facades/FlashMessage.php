<?php

namespace AppView\Helpers\Facades;

use Plasticbrain\FlashMessages\FlashMessages;
use VatGia\Helpers\Facade;

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/6/18
 * Time: 02:10
 */

/**
 * Class FlashMessage
 * @package AppView\Helpers
 *
 * @method static FlashMessages error($message, $redirectUrl = null, $sticky = false)
 * @method static FlashMessages success($message, $redirectUrl = null, $sticky = false)
 * @method static FlashMessages hasErrors()
 * @method static FlashMessages hasMessages()
 * @method static FlashMessages display()
 */
class FlashMessage extends Facade
{

    protected static function getFacadeAccessor()
    {
        return new FlashMessages;
    }

}