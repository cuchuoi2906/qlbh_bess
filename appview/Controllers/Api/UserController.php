<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/28/19
 * Time: 00:02
 */

namespace AppView\Controllers\Api;


class UserController extends ApiController
{

    public function getNewUsers()
    {

        $limit = $this->input['limit'] ?? 10;

        $result = model('users/new')->load([
            'limit' => (int)$limit
        ]);

        return $result['vars'];

    }

    public function getIndex()
    {

        $result = model('users/index')->load($_GET);

        return $result['vars'];
    }

    public function getBestSeller()
    {
        $result = model('users/best_seller')->load($_GET);

        return $result['vars'];
    }

    public function getDetail($id)
    {
        $result = repository('users/get_by_id')->load([
            'id' => (int)$id
        ]);

        return $result['vars'];
    }
    public function deleteRemove($id)
    {
        $result = repository('users/delete')->load([
            'id' => (int)$id
        ]);
        return $result['vars'];
    }

}