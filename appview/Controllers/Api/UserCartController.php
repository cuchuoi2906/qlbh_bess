<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 12:47
 */

namespace AppView\Controllers\Api;


class UserCartController extends ApiController
{

    public function getIndex()
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }

        $result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);
        $_SESSION['cartTotalProduct'] = $result['vars']['meta']['total_product_cart'];

        return $result['vars'];
    }

    public function postAdd()
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $product_id = getValue('product_id', 'int', 'POST', 0, 0);
        $quantity = getValue('quantity', 'int', 'POST', 0, 0);

        model('user_cart/add')->load([
            'user_id' => $_SESSION['userIdFe'],
            'product_id'=>$product_id,
            'quantity'=>$quantity,
            'is_add_more'=>0
        ] + $this->input);

        return $this->getIndex();
    }

}