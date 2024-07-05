<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 1:47 PM
 */

namespace AppView\Controllers;


class ProductController extends FrontEndController
{
    public function getProducts()
    {
        $page = getValue('page', 'int', 'GET', 1, 0);
        $keyword = getValue('keyword', 'str', 'GET', '', 0);
        $is_hot = getValue('is_hot', 'int', 'GET', -1);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > 999999999) {
            $page = 999999999;
        }

        $params = [
            'page' => $page,
            'sort_by' => getValue('sort_by', 'str', 'GET', '', 2),
            'sort_type' => getValue('sort_type', 'str', 'GET', 'DESC', 2),
            'category_id' => getValue('category_id', 'int', 'GET', 0, 0),
            'page_size' => 10,
            'keyword' => $keyword,
            'is_hot' => $is_hot
        ];

        $data = model('products/index')->load($params);

        return view('posts/detail')->render([
            'item' => $detail,
            'postCategory'=>$postCategory
        ]);
    }

    public function getProductDetail($pro_id)
    {
        $pro_id = (int)$pro_id;

        $data = model('products/get_by_id')->load([
            'id' => $pro_id
        ] + $this->input);

        if (!$data['vars']) {
            throw new \Exception('Sản phẩm không tồn tại', 404);
        }

        return $data['vars'];
    }

    public function postLike($product_id)
    {

        $result = repository('products/like')->post([
            'product_id' => (int)$product_id,
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }

    public function postUnlike($product_id)
    {

        $result = repository('products/unlike')->post([
            'product_id' => (int)$product_id,
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }

    public function getLiked()
    {

        $result = repository('products/liked')->post([
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }
}