<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/14/19
 * Time: 09:36
 */

$vars = false;
$user_id = (int)input('user_id');
$notification_id = (int)input('notification_id');

//Check notification
$notification = \App\Models\Notification::findByID($notification_id);
if ($notification) {
    //Check user notification
    $check = \App\Models\NotificationStatus::where('(nts_user_id = ' . $user_id . ' OR nts_user_id = 0) AND nts_notification_id = ' . $notification_id . '')->find();
    if ($check) {
        $affected_row = \App\Models\NotificationStatus::insertUpdate([
            'nts_notification_id' => $notification_id,
            'nts_user_id' => $user_id,
            'nts_status' => 1,
            'nts_read_at' => time()
        ], [
            'nts_status' => 1,
            'nts_read_at' => time()
        ]);
        $vars = (boolean)$affected_row;
    }
}

return [
    'vars' => $vars
];