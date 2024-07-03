<?php
/**
 * Created by vatgia-framework.
 * Date: 6/28/2017
 * Time: 11:54 AM
 */

namespace AppView\Repository;


interface PostRepositoryInterface
{
    public function getByID($id);

    public function getPostsLimit($category_id = null, $type = null, $limit = 5, $hot = true, $id = 0);

    public function allByType($type, $page_size = 3);

    public function allByCat($category_id, $category_type);
}