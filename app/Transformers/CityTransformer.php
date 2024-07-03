<?php
/**
 * Created by PhpStorm.
 * User: Minh Vu
 * Date: 30/10/2018
 * Time: 16:39
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'agency'
    ];

    public function transform($item)
    {
        return [
            'id' => (int)$item->id,
            'name' => $item->name,
            'area' => $item->area,
            'latitude' => $item->cit_map_latitude,
            'longitude' => $item->cit_map_longitude
        ];
    }

    public function includeAgency($item)
    {
        $item->agency = $item->agency ?? [];

        return $this->collection($item->agency, new AgencyTransformer());
    }
}