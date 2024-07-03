<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/29/18
 * Time: 20:38
 */

namespace AppView\Repository;


class OrderRepository
{


    public function create($name, $phone, $email, $address, $products, $note = '', $payment_type = 'COD')
    {

        $result = model('orders/create')->load([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'products' => $products,
            'note' => $note,
            'payment_type' => $payment_type
        ]);

        return $result['vars'];
    }

    public function detail($order_id)
    {
        $result = model('orders/detail')->load([
            'id' => $order_id
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function detailFromCode($code)
    {
        $result = model('orders/detail')->load([
            'code' => $code
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function allByUserId($user_id)
    {
        $result = model('orders/index')->load([
            'user_id' => $user_id
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }
}