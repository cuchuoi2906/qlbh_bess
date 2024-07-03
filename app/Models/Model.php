<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/12/18
 * Time: 12:00
 */

namespace App\Models;


class Model extends \VatGia\Model\Model
{

    protected $localeFields = [];

    public function __get($name)
    {
        if (substr($name, -6) === 'Locale') {
            return $this->{str_replace('Locale', '_' . locale(), $name)};
        }

        if (in_array($name, $this->localeFields) || in_array($this->prefix . '_' . $name, $this->localeFields)) {
            return $this->{$name . '_' . locale()};
        }

        return parent::__get($name); // TODO: Change the autogenerated stub
    }

}