<?php
/**
 * Created by vatgia-framework.
 * Date: 6/26/2017
 * Time: 5:31 PM
 */

namespace AppView\Controllers\Api;

class PostDetailController extends ApiController
{

    public function getPostDetail($id)
    {
        $id = (int)$id ?? 0;

        $data = model('posts/get_by_id')->load([
            'id' => $id
        ]);

        if (!$data['vars']) {
            throw new \RuntimeException('Bài viết không tồn tại', 404);
        }

        return $data['vars'];
    }

}