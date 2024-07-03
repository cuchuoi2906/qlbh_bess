<?php

use App\Models\Setting;

$code = input('code');
if (!$code) {
    $value = null;
}

$value = Setting::where('swe_key = \'' . $code . '\'')->first();

return [
    'vars' => $value ? $value : null,
];