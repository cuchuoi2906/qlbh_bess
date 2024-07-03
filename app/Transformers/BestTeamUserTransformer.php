<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/24/19
 * Time: 23:06
 */

namespace App\Transformers;


use App\Models\Users\Users;
use League\Fractal\TransformerAbstract;

class BestTeamUserTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'user'
    ];

    public $defaultIncludes = [
        'user'
    ];

    /**
     * @param Users $user
     * @return array
     */
    public function transform($item)
    {

        return [
            'quantity' => $item->total_point,
            'quantity_display' => number_format($item->total_point),
            'money' => $item->total_money,
            'money_display' => number_format($item->total_money),
            'money_point' => $item->total_money_point,
            'money_point_display' => number_format($item->total_money_point),
        ];
    }

    public function includeUser($item)
    {

        return $this->item($item->user, new UserTransformer());
    }

}