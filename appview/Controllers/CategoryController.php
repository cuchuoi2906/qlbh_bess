<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 9:24 AM
 */

namespace AppView\Controllers;


class CategoryController extends FrontEndController
{

    public function getCategory()
    {

        $type = $this->input['type'] ?? 'PRODUCT';

        $data = model('categories/index')->load([
            'type' => $type
        ]);

        return $data['vars'];
    }
    public function getCategoryList($type = '',$parent_id = 0)
    {

        $type = $type ?? 'NEWS';

        $data = model('categories/index')->load([
            'type' => $type,
            'parent_id'=>$parent_id
        ]);

        return $data['vars'];
    }
}