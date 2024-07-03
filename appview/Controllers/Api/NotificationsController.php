<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2019
 * Time: 1:23 PM
 */

namespace AppView\Controllers\Api;


class NotificationsController extends ApiController
{
    public function getNotifications()
    {
        $page = getValue('page', 'int', 'GET', 1, 0);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > 999999999) {
            $page = 999999999;
        }

        $read = getValue('read', 'int', 'GET', 0);

        $data = model('notifications/index')->load([
            'user_id' => (int)app('auth')->u_id,
            'read' => (int)$read,
            'page' => $page,
            'page_size' => 10
        ]);

        if ($read) {
            foreach ($data['vars']['data'] ?? [] as $notify) {
                if ($notify['id']) {
                    model('notifications/read')->load([
                        'user_id' => app('auth')->u_id,
                        'notification_id' => $notify['id']
                    ]);
                }

            }
        }

        return $data['vars'];
    }

    public function postRead($id)
    {
        $result = model('notifications/read')->load([
            'user_id' => (int)app('auth')->u_id,
            'notification_id' => (int)$id
        ]);

        return $this->input;
    }
}