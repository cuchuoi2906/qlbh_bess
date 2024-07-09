<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-02-28
 * Time: 13:57
 */

namespace AppView\Controllers\Api;

class OrderController extends ApiController
{
    public function postOrder()
    {
        $data = model('order/post_order_v3')->load(
            ['user_id' => $_SESSION['userIdFe']]
            + $this->input
        );

        return $data;
    }

    public function getOrders()
    {
        $data = model('order/orders')->load([
                'user_id' => (int)app('auth')->u_id
            ] + $this->input);

        return $data['vars'];
    }

    public function getSubOrders()
    {
        $result = model('order/order_sub_list')->load([
                'user_id' => (int)app('auth')->u_id
            ] + $this->input);

        return $result['vars'];
    }

    public function deleteOrder($id)
    {

        $result = model('order/delete')->load([
                'user_id' => (int)app('auth')->u_id,
                'id' => (int)$id,
            ] + $this->input);

        return $result['vars'];
    }

    public function putOrder($id)
    {

        $result = model('order/renew')->load([
                'user_id' => (int)app('auth')->u_id,
                'id' => (int)$id,
            ] + $this->input);

        return $result['vars'];
    }

    public function getUserOrders($id)
    {

        $response = repository('order/get_order_by_user_id')->get([
                'user_id' => (int)$id
            ] + $this->input);

        return $response['vars'];
    }


}