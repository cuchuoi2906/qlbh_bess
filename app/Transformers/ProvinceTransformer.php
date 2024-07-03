<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 09:58
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class ProvinceTransformer extends TransformerAbstract
{

    public function transform($item)
    {

        return [
            'id' => (int)$item->id,
            'name' => $item->name,
            'type' => $item->type
        ];
    }

}