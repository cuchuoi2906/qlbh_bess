<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 09/05/2021
 * Time: 14:44
 */

namespace App\Transformers;


use App\Models\TopRacingCampaign;
use League\Fractal\TransformerAbstract;

class TopRacingCampaignTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'products'
    ];

    public $defaultIncludes = [
        'products'
    ];

    public function transform($item)
    {

        return [
            'id' => (int)$item->id,
            'title' => $item->title,
            'description' => html_entity_decode($item->description),
            'start' => date('d/m/Y', $item->start),
            'end' => $item->end ? date('d/m/Y', $item->end) : 0,
            'all_product' => (int)$item->all_product,
            'status' => ($item->active && $item->start < time() && ($item->end == 0 || $item->end > time())) ? 'Đang chạy' : 'Đã dừng',
            'type' => $item->type,
            'typeDisplay' => TopRacingCampaign::TYPES[$item->type],
        ];
    }

    public function includeProducts($item)
    {

        return $this->collection($item->products, new ProductTransformer());
    }
}