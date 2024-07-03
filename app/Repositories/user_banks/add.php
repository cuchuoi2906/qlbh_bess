<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 01:58
 */

$id = \App\Models\Users\UserBank::insert([
    'usb_user_id' => (int)input('user_id'),
    'usb_bank_name' => input('bank_name'),
    'usb_account_name' => input('account_name'),
    'usb_account_number' => input('account_number'),
    'usb_branch' => input('branch')
]);

return [
    'vars' => $id
];