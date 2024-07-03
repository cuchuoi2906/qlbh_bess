<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-03
 * Time: 10:59
 */

namespace AppView\Controllers\Api;


use App\Models\Users\Users;

class TestApiController
{

    public function test()
    {
        $user = Users::findByID('4');
        $config_all = model('settings_website/index')->load();
//        dd($config_all['vars']);

        $threshold = $user->getValueThreshold(collect($config_all['vars']));
        $commision = $user->getValueCommission(collect($config_all['vars']));
//        dd($commision);
//        dd(config('user_level_2_commission'));
        $result = model('order/post_order')->load([
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => '2'
                ],
                [
                    'product_id' => 2,
                    'quantity' => 3,
                ],
                [
                    'product_id' => 4,
                    'quantity' => 1,
                ]
            ],
            'user_id' => 4,
            'address' => "Thanh tri ha noi",
            'name' => "Tung Anh",
            'phone' => "098611104",
            'email' => 'tunganh@gmail.com',
            'note' => ' chi tiết note'
        ]);



//        $result = model('users/get_users_referral')->load([
//            'referral_id' => 4
//        ]);
//
////        dd($result);
//        return $result['vars'];

    }

    public function getIndex($code)
    {
        //Lấy slider từ vị trí
        $result = model('User/from_code')->load([
            'code' => $code
        ]);

        return $result['vars'];
    }

}