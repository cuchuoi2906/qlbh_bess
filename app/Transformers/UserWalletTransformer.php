<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 18:53
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class UserWalletTransformer extends TransformerAbstract
{

    public function transform($item)
    {

        return [
            'commission' => $item->commission ?? 0,
            'charge' => $item->charge ?? 0
        ];
    }

}