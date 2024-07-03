<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 3:13 PM
 */

namespace AppView\Controllers\Api;


class ProductImagesController extends ApiController
{
    public function getImages($pro_id)
    {
        $pro_id = (int)$pro_id;

        $data = model('products/get_images')->load([
            'pro_id' => $pro_id
        ]);

        return $data['vars'];
    }
}