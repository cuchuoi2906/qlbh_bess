<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/2/19
 * Time: 23:34
 */
require_once 'inc_security.php';
$type = getValue('type', 'str', 'GET', '');
$response = '';
switch ($type) {
    case 'CATEGORY':
        $items = \App\Models\Categories\Category::all();
        if ($items->count()) {
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' . $item->type . '-' . $item->name . '</option>';
            }
        }
        break;
    case 'PRODUCT':
        $items = \App\Models\Product::all();
        if ($items->count()) {
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }
        break;
    case 'NEWS':
        $items = \App\Models\Post::where('pos_type', 'NEWS')->all();
        if ($items->count()) {
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' . $item->title . '</option>';
            }
        }
        break;
    case 'VIDEO':
        $items = \App\Models\Post::where('pos_type', 'VIDEO')->all();
        if ($items->count()) {
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' . $item->title . '</option>';
            }
        }
        break;
    case 'TOP_RACING':
        $items = \App\Models\TopRacingCampaign::all();
        if ($items->count()) {
            foreach ($items as $item) {
                $response .= '<option value="' . $item->id . '">' . $item->title . '</option>';
            }
        }
        break;
}

echo $response;
die;
