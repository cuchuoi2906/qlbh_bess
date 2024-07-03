<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2019
 * Time: 2:00 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class NotificationStatusTransformer extends TransformerAbstract
{

    public $defaultIncludes = [
//        'notification',
    ];

    public function transform($item)
    {

        return [
            'id' => (int)$item->notification_id,
            'type' => $item->notification->type,
            'title' => $item->notification->title,
            'content' => $item->notification->content,
            'status' => (int)$item->status,
            'read_at' => (int)$item->read_at,
            'created_at' => new \DateTime($item->notification->created_at)
        ];
    }

    public function includeNotification($item)
    {
        $notification = $item->notification ? $item->notification : collect([]);

        return $this->item($notification, new NotificationsTransformer());
    }
}