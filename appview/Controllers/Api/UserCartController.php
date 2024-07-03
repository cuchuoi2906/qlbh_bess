<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 12:47
 */

namespace AppView\Controllers\Api;


class UserCartController extends ApiController
{

    public function getIndex()
    {

        $result = model('user_cart/index')->load([
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }

    public function postAdd()
    {
        model('user_cart/add')->load([
                'user_id' => app('auth')->u_id
            ] + $this->input);

        return $this->getIndex();
    }

}