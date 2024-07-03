<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/12/18
 * Time: 01:18
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class ProductImageTransformer extends TransformerAbstract
{

    public function transform($image)
    {
        if (!$image) {
            return [
                'name' => '',
                'url' => '/assets/v2/images/no-img.jpg'
            ];
        }
        return [
            'name' => $image->file_name,
            'url' => url() . '/upload/products/' . $image->file_name
        ];
    }

}