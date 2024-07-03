<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 21:21
 */

namespace AppView\Controllers\Api;


class UserMoneyLogController extends ApiController
{

    public function getIndex()
    {

        $result = model('user_money_logs/index')->load([
            'user_id' => (int)app('auth')->u_id,
            'type' => getValue('type', 'str', 'GET', null),
            'source_type' => getValue('source_type', 'str', 'GET', null),
            'page' => getValue('page', 'int', 'GET', 1),
            'page_size' => getValue('page_size', 'int', 'GET', 5),
        ]);

        return $result['vars'];
    }

}