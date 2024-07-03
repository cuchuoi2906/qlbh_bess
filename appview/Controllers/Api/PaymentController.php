<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-18
 * Time: 06:28
 */

namespace AppView\Controllers\Api;


use App\Models\Users\UserMoneyAddRequest;

class PaymentController extends ApiController
{

    public function getBanks()
    {
        $result = model('payment/banks')->load();

        return $result['vars'];
    }

    public function postRequest()
    {
        $data = model('user_money_add_request/create')->load([
                'user_id' => app('auth')->u_id,
                'type' => UserMoneyAddRequest::TYPE_MONEY_ADD,
            ]
            + $this->input
        );

        return $data['vars'];
    }

}

