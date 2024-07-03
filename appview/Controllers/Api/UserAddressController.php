<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 01:01
 */

namespace AppView\Controllers\Api;


class UserAddressController extends ApiController
{

    public function getIndex()
    {

        $result = model('user_address/index')->load([
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];

    }

    public function postAdd()
    {

        $result = model('user_address/add')->load(
            [
                'user_id' => app('auth')->u_id
            ] + $this->input
        );

        if (!$result['vars']) {
            throw new \Exception('Tạo mới không thành công', 400);
        }

        return [
            'id' => $result['vars']
        ];
    }

    public function putEdit($id)
    {

        $result = model('user_address/update')->load(
            [
                'id' => $id,
                'user_id' => app('auth')->u_id
            ] + $this->input
        );

        return [
            'id' => $id
        ];
    }

    public function deleteRemove($id)
    {
        $result = model('user_address/delete')->load([
            'id' => $id,
            'user_id' => app('auth')->u_id
        ]);
    }
}