<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/8/19
 * Time: 00:21
 */

namespace AppView\Helpers;


use App\Workers\SendNotificationWorker;
use VatGia\Queue\Facade\Queue;

class Notification
{

    public static function to($ids, $title, $content, $type = 'SYSTEM')
    {

        $result = model('notifications/push')->load([
            'user_ids' => (array)$ids,
            'title' => $title,
            'content' => $content,
            'type' => $type
        ]);

        if ($result['vars']) {
            Queue::pushOn(SendNotificationWorker::$name, SendNotificationWorker::class, [
                'id' => $result['vars']
            ]);
        }

        return $result['vars'];
    }
}