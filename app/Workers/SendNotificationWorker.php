<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/3/19
 * Time: 15:52
 */

namespace App\Workers;


use App\Models\Notification;
use App\Models\NotificationStatus;

class SendNotificationWorker
{

    public static $name = 'send_notification';

    //TOPIC_USER_{id}
    //TOPIC_USER_ALL
    public function fire($data)
    {

        $id = $data['id'] ?? 0;
        $notification = Notification::findByID($id);

        if ($notification->is_send_all) {
            echo send_message_to_firebase('TOPIC_USER_ALL', $notification->title, $notification->content);
        } else {
            $notification_status = NotificationStatus::where('nts_notification_id', $id)->all();
            foreach ($notification_status as $item) {
                echo send_message_to_firebase('TOPIC_USER_' . $item->user_id, $notification->title, $notification->content);
                echo PHP_EOL;
            }
        }
    }

}