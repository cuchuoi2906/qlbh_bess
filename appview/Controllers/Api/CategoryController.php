<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 9:24 AM
 */

namespace AppView\Controllers\Api;


class CategoryController extends ApiController
{

    public function getCategory()
    {

        $type = $this->input['type'] ?? 'PRODUCT';

        $data = model('categories/index')->load([
            'type' => $type
        ]);

        return $data['vars'];
    }
}