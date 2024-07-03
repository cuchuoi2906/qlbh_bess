<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/3/19
 * Time: 09:19
 */

namespace AppView\Controllers\Api;


use Exception;

class UserDeviceController extends ApiController
{

    public function postAdd()
    {

        $result = model('users/add_device')->load($this->input + [
                'id' => (int)app('auth')->u_id
            ]);

        if (!$result['vars']) {
            throw new Exception('Thêm thiết bị không thành công', 400);
        }

    }

    public function delete($device_id)
    {
        $result = model('users/delete_device')->load([
            'id' => (int)app('auth')->u_id,
            'registration_id' => $device_id
        ]);

        if (!$result['vars']) {
            throw new Exception('Xóa thiết bị không thành công', 400);
        }
    }

}