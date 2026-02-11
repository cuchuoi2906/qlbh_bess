<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 02:01
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class UserAdminHapuTransformer extends TransformerAbstract
{

    public function transform($item)
    {

        return [
            'id' => (int)$item->id,
            'name' => $item->name,
            'loginname' => $item->loginname
        ];
    }

}