<?php

function _setting($key, $default)
{
    $setting = \App\Models\Setting::where('swe_key', $key)->first();
    if(!$setting)
    {
        return $default;
    }
    return $setting->value;
}