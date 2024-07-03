<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2019
 * Time: 1:43 PM
 */

namespace App\Models;


class NotificationStatus extends Model
{

    public $table = 'notification_status';
    public $prefix = 'nts';


    public function notification()
    {

        return $this->hasOne(
            __FUNCTION__,
            Notification::class,
            'not_id',
            'nts_notification_id'
        );
    }

}