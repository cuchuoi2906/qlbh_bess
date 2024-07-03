<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 1/15/20
 * Time: 06:35
 */

namespace AppView\Controllers\Api;


class MoneyBankingRequestController extends ApiController
{

    public function postRequest()
    {
        $response = model('money_add_request_notify/create')->load(['user_id' => app('auth')->u_id] + $this->input);

        return $response['vars'];
    }

}