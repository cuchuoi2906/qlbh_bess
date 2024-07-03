<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 1/4/20
 * Time: 13:43
 */

namespace App\Workers;


use App\Models\Users\Users;

class CountChildUserWorker
{

    public static $name = 'count_childs_user';

    /**
     * @param $data
     * @throws \Exception
     */
    public function fire($data)
    {

        $user = Users::withTrash()->findByID($data['id'] ?? 0);
        while ($user) {
            echo 'Update thống kê thành viên cho user ' . $user->id . PHP_EOL;
            $total_direct = Users::where('use_active', 1)
                ->where('use_referral_id', $user->id)
                ->count();

            $total_referer = Users::where('use_all_level', 'like', '%.' . $user->id . '.%')
                ->where('use_active', 1)
                ->count();

            $user->total_direct_refer = (int)$total_direct;
            $user->total_refer = (int)$total_referer;
            $user->update();

            $user = $user->parent ?? false;
        }

    }
}