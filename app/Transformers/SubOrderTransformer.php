<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/21/19
 * Time: 00:07
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class SubOrderTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'order',
        'user',
        'products'
    ];

    public $defaultIncludes = [
        'order',
        'user'
    ];

    public function transform($item)
    {

        return [
            'id' => (int)$item->order_id,
            'commission' => $item->amount
        ];
    }

    public function includeOrder($item)
    {
        return $this->item($item->order, new OrderTransformer());
    }

    public function includeProducts($item)
    {
        return $this->collection($item->products, new OrderProductTransformer());
    }

    public function includeUser($item)
    {
        return $this->item($item->order->user, new UserTransformer());
    }

}