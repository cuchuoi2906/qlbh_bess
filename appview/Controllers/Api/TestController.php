<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 25/04/2021
 * Time: 22:25
 */

namespace AppView\Controllers\Api;


class TestController extends ApiController
{


    public function postTest()
    {

        model('user_cart/add')->load([
                'user_id' => app('auth')->u_id
            ] + $this->input);

        return $this->getIndex();
    }

    public function getIndex()
    {

        $result = model('user_cart/index')->load([
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }
}