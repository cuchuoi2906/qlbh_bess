<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 19:01
 */

namespace AppView\Controllers\Api;


class UserWalletController extends ApiController
{

    public function postTransfer()
    {

        $money = $this->input['money'] ?? 0;
        $money = str_replace('.', '', $money);
        $money = str_replace(',', '', $money);
        $this->input['money'] = $money;

        $result = model('user_wallet/transfer')->load(
            ['user_id' => app('auth')->u_id]
            + $this->input
        );

        return $result['vars'];
    }

}