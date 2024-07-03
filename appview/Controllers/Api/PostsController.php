<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/28/2019
 * Time: 10:31 AM
 */

namespace AppView\Controllers\Api;


class PostsController extends ApiController
{
    public function getPosts()
    {
        $page = getValue('page', 'int', 'GET', 1, 0);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > 999999999) {
            $page = 999999999;
        }

        $params = [
            'page' => $page,
            'pos_category_id' => getValue('pos_category_id', 'int', 'GET', 0, 0),
            'type' => getValue('type', 'str', 'GET', '', 2),
            'page_size' => 10,

        ];

        $data = model('posts/index')->load($params);

        return $data['vars'];
    }
}