<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 02:04
 */

$data = [];
if (input('bank_name')) {
    $data['bank_name'] = input('bank_name');
}
if (input('account_name')) {
    $data['bank_name'] = input('bank_name');
}
if (input('account_number')) {
    $data['account_number'] = input('account_number');
}
if (input('branch')) {
    $data['branch'] = input('branch');
}

$affected = \App\Models\Users\UserBank::update($data, 'usb_id = ' . (int)input('id') . ' AND usb_user_id = ' . input('user_id'));

return [
    'vars' => $affected
];