<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/2/19
 * Time: 23:34
 */
require_once 'inc_security.php';
$type = getValue('type', 'str', 'GET', '');
$value = getValue('value', 'int', 'GET', '');
$response = '';
switch ($type) {
    case 'district':
        $items = \App\Models\District::where('dis_province_id','=',$value);
        if ($items->count()) {
            $items = $items->all();
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' .$item->name . '</option>';
            }
        }
        break;
    case 'ward':
        $items = \App\Models\Ward::where('war_district_id','=',$value);
        if ($items->count()) {
            $items = $items->all();
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' .$item->name . '</option>';
            }
        }
        break;
}

echo $response;
die;