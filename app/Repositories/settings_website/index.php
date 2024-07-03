<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 2/28/2019
 * Time: 9:29 AM
 */

use \App\Models\Setting;
use \App\Transformers\SettingTransformer;

$vars = [];

$items = Setting::order_by('swe_id', 'DESC')->all();

if ($items->count()) {
    $vars = transformer_collection($items, new SettingTransformer());
}

return [
    'vars' => $vars
];