<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-12
 * Time: 09:45
 */
use \App\Models\Order;
use App\Models\Users\Users;
use App\Transformers\OrderTransformer;
$vars = [];

$order = Order::findByID(input('order_id'));
if(!$order){
    throw new \Exception("Order không tồn tại");
}
$user = Users::findByID(input('user_id'));
if(!$user){
    throw new \Exception("user id không tồn tại");
}

$orderManager = new App\Manager\Order\OrderManager();
$order = $orderManager->paidOrder($order,$user);
$vars =  transformer_item($order, new OrderTransformer());



return [
    'vars' => $vars
];