<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/8/19
 * Time: 00:23
 */

$vars = [];

$user_ids = (array)input('user_ids');
$title = input('title');
$content = input('content');
$type = input('type') ?? 'SYSTEM';

$notify_id = \App\Models\Notification::insert([
    'not_title' => $title,
    'not_content' => $content,
    'not_type' => $type,
    'not_is_send_all' => empty($user_ids) ? 1 : 0,
]);

if ($notify_id) {
    if (empty($user_ids)) {
        $user_ids = App\Models\Users\Users::all();
        $user_ids = $user_ids->lists('use_id');
    }


    foreach ($user_ids as $user_id) {
        \App\Models\NotificationStatus::insert([
            'nts_notification_id' => $notify_id,
            'nts_user_id' => (int)$user_id,
        ]);
    }

    $vars = $notify_id;
}


return [
    'vars' => $vars
];