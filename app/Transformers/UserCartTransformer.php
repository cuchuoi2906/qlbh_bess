<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 14:13
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class UserCartTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'product'
    ];

    public $defaultIncludes = [
        'product'
    ];

    public function transform($item)
    {

        return [
            'quantity' => (int)$item->quantity
        ];
    }

    public function includeProduct($item)
    {
        return $this->item($item->product, new ProductTransformer());
    }

}