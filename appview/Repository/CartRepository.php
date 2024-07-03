<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 16/10/2018
 * Time: 16:18
 */

namespace AppView\Repository;


class CartRepository implements CartInterface
{
    /**
     * @return \VatGia\Helpers\Collection
     */
    public function allByCookie()
    {
        $result = model('cart/all_by_cookie')->load([]);

        return collect_recursive($result['vars']);
    }

    /**
     * @param $product_id
     * @param $quantity
     * @return mixed
     */
    public function addToCart($product_id, $quantity)
    {
        $result = model('cart/add_to_cart')->load([
            'product_id' => (int)$product_id,
            'quantity' => (int)$quantity
        ]);

        return $result['vars'];
    }

    /**
     * @param $product_id
     * @param $quantity
     * @param $price_item
     * @param $total_money_order
     * @return mixed
     */
    public function updateToCart($product_id, $quantity, $price_item, $total_money_order)
    {
        $result = model('cart/update_to_cart')->load([
            'product_id' => (int)$product_id,
            'quantity' => (int)$quantity,
            'price_item' => $price_item,
            'total_money_order' => $total_money_order,
        ]);

        return $result['vars'];
    }

    function delete($product_id)
    {
        $result = model('cart/remove_to_cart')->load([
            'product_id' => (int)$product_id
        ]);

        return ($result['vars']) ? true : false;
    }

    function clear()
    {
        $result = model('cart/clear')->load([]);

        return ($result['vars']) ? true : false;
    }
}