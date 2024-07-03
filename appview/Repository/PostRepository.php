<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen
 * Date: 4/27/2017
 * Time: 5:14 PM
 */

namespace AppView\Repository;


class PostRepository implements PostRepositoryInterface
{

    public function allByType($type, $page_size = 3)
    {
        $result = model('posts/index')->load([
            'pos_type' => $type,
            'page' => getValue('page'),
            'page_size' => (int)$page_size
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function allByCat($category_id, $category_type)
    {
        $result = model('posts/index')->load([
            'category_id' => (int)$category_id,
            'pos_type' => $category_type,
            'page' => getValue('page'),
            'page_size' => 4
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function driverByCat($category_id)
    {
        $result = model('posts/get_driver_by_cat')->load([
            'category_id' => (int)$category_id,
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function getByID($id)
    {
        $result = model('posts/get_by_id')->load([
            'id' => (int)$id
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function getPostsLimit($category_id = null, $type = null, $limit = 5, $hot = true, $id = 0)
    {
        $result = model('posts/get_new_posts')->load([
            'category_id' => (int)$category_id,
            'pos_type' => $type,
            'limit' => (int)$limit,
            'hot' => $hot,
            'pos_id' => (int)$id,
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function listing($category_id, $page = 1, $page_size = 7, $keyword = '')
    {
        $result = model('posts/index')->load([
            'category_id' => (int)$category_id,
            'page' => (int)$page,
            'page_size' => (int)$page_size,
            'keyword' => $keyword
        ]);

        return collect_recursive($result['vars']);
    }

    public function visited($id)
    {
        model('posts/visited')->load([
            'id' => $id
        ]);
    }
}