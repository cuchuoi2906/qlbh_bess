<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 02:07
 */

\App\Models\Users\UserBank::where('usb_id', input('id'))
    ->where('usb_user_id', input('user_id'))
    ->delete();

return [
    'vars' => true
];