<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-19
 * Time: 18:13
 */

namespace App\Transformers;


class UserMoneyAddRequestTransformer extends TransformerAbstract
{
    public $availableIncludes = [
        'user',
    ];


    public function transform($item)
    {
        $types = UserMoneyLog::types();
        return [
            'id' => (int)$item->id,
//            'code' => $item->code,
//            'link' => url('order.detail', ['code' => $item->code]),
            'amount' => $item->amount,
            'amount_formatted' => number_format($item->amount),
            'note' => $item->note,
            'type' => $types[$item->type],
            'created_at' => new \DateTime($item->created_at),
        ];
    }

    public function includeUser($user)
    {
        $wallet = $user->wallet ?? collect([]);

        return $this->item($wallet, new UserWalletTransformer());
    }

}