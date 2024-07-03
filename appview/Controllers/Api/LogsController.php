<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-09
 * Time: 11:50
 */

namespace AppView\Controllers\Api;


class LogsController extends ApiController
{
    public function getCommissions()
    {
        $user_id = isset($this->input['user_id']) ? $this->input['user_id'] : 0;
        if((int)app('auth')->u_id != $user_id){
            throw new \Exception("Xác thực user đăng nhập sai");
        }

        $data = model('logs/commissions')->load([
            'user_id' => $user_id,
            'page' => 1,
        ]);
        return $data['vars'];

    }

    public function getMoneyAdds(){
        $user_id = isset($this->input['user_id']) ? $this->input['user_id'] : 0;
        if((int)app('auth')->u_id != $user_id){
            throw new \Exception("Xác thực user đăng nhập sai");
        }

        $data = model('logs/moneyadds')->load([
            'user_id' => $user_id,
            'page' => 1,
        ]);

        return $data['vars'];

    }

}