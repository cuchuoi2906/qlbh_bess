<?php
/**
 * Created by amall.
 * Date: 8/30/2017
 * Time: 3:11 PM
 */

namespace App\Models;


class Setting extends Model
{

    public $table = 'settings_website';
    public $prefix = 'swe';

    public $localeFields = [
        'value'
    ];

}