<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-19
 * Time: 11:14
 */

namespace App\Manager\MoneyAdd;

use App\Models\UserMoneyLog;
use App\Models\Users\UserWallet;
use App\Models\Users\Users;

class MoneyAddManager
{
    public function add_money($userId, $amount, $note = '')
    {
        $user = Users::findByID($userId);

        if (!($user->wallet ?? false)) {
            $current = $amount;
            UserWallet::insert([
                'usw_user_id' => $userId,
                'usw_charge' => $current,
            ]);
        } else {
            $current = $user->wallet->charge + $amount;
            $user->wallet->charge = $current;
            $user->wallet->update();
        }

        $logMoneyId = UserMoneyLog::insert([
            'uml_user_id' => $userId,
            'uml_amount' => $amount,
            'uml_current' => $current,
            'uml_type' => UserMoneyLog::TYPE_MONEY_ADD,
            'uml_note' => $note
        ]);

    }

}