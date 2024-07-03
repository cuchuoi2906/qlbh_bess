<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 16/10/2018
 * Time: 16:12
 */

namespace AppView\Repository;


interface CartInterface
{
    function allByCookie();

    function addToCart($product_id, $quantity);

    function updateToCart($product_id, $quantity, $price_item, $total_money_order);

    function delete($product_id);

    function clear();
}