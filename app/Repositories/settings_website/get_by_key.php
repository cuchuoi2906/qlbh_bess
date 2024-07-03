<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-05
 * Time: 13:49
 */

use \App\Models\Setting;
use \App\Transformers\SettingTransformer;

$vars = [];

$item = Setting::where('swe_key', input('key'))->order_by('swe_id', 'DESC')->first();

if ($item) {
    $vars = transformer_item($item, new SettingTransformer());
}

return [
    'vars' => $vars
];