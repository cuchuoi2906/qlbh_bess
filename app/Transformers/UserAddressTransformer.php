<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/24/19
 * Time: 23:06
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class UserAddressTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'ward',
        'district',
        'province'
    ];

    public $defaultIncludes = [
        'ward',
        'district',
        'province'
    ];

    public function transform($item)
    {

        if (!($item->id ?? false)) {
            return [];
        }
        $data = [

            'id' => (int)$item->id,
            'title' => $item->title,
            'name' => $item->name,
            'phone' => $item->phone,
            'address' => $item->address,
            'is_main' => (int)$item->is_main
        ];
        return $data;
    }

    public function includeWard($item)
    {

        return $this->item($item->ward ?? collect([]), new WardTransformer());
    }

    public function includeDistrict($item)
    {

        return $this->item($item->district ?? collect([]), new WardTransformer());
    }

    public function includeProvince($item)
    {

        return $this->item($item->province ?? collect([]), new WardTransformer());
    }

}