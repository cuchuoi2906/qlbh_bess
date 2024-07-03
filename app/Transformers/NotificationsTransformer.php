<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2019
 * Time: 2:00 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class NotificationsTransformer extends TransformerAbstract
{
    public function transform($item)
    {

//        if (!($item ?? false) || !($item->notification ?? false)) {
//            return [];
//        }

        return [
            'id' => (int)$item->notification_id,
            'type' => $item->notification->type,
            'title' => $item->notification->title,
            'content' => $item->notification->content,
            'status' => (int)$item->status_max,
            'read_at' => (int)$item->read_at_max,
        ];
    }
}