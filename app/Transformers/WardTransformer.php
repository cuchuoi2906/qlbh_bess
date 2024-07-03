<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/18/20
 * Time: 10:02
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class WardTransformer extends TransformerAbstract
{

    public function transform($item)
    {

        if (!($item->id ?? false)) {
            return [];
        }

        return [
            'id' => (int)$item->id,
            'name' => $item->name,
            'type' => $item->type,
            'location' => $item->location,
        ];
    }

}