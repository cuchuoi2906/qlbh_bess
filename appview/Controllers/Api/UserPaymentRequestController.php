<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 21:36
 */

namespace AppView\Controllers\Api;


class UserPaymentRequestController extends ApiController
{

    public function postAdd()
    {
        $result = model('users/payment_request')->load([
                'user_id' => app('auth')->u_id,
            ] + $this->input);

        return $result['vars'];
    }

}