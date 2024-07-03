<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 01:23
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class UserBankTransformer extends TransformerAbstract
{

    public function transform($bank)
    {

        return [
            'id' => (int)$bank->id,
            'name' => $bank->bank_name,
            'account_name' => $bank->account_name,
            'account_number' => $bank->account_number,
            'branch' => $bank->branch
        ];
    }

}