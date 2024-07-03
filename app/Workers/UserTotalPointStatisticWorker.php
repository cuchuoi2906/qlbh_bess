<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/2/20
 * Time: 09:02
 */

namespace App\Workers;


use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\OrderCommit;
use App\Models\Users\Users;
use SebastianBergmann\CodeCoverage\Report\PHP;

class UserTotalPointStatisticWorker
{

    public static $name = 'user_total_point_statistic';

    public function fire($data)
    {

        $user_id = $data['user_id'] ?? 0;

        $user = Users::findByID($user_id);
        if (!$user) {
            throw new \Exception('User không tồn tại');
        }

        self::userCountTotalPointTeam($user_id);


        if ($parent = $user->parent) {
            echo 'Update total commission của user ' . $parent->id . PHP_EOL;
            self::userCountTotalPointTeam($user->parent->id);
        } else {
            echo 'User không có parent không tồn tại' . PHP_EOL;
        }
    }

    public static function userCountTotalPointTeam($user_id)
    {
        /**
         * @var $user Users
         */
        $user = Users::findByID($user_id);

        echo 'Tính tổng point team cho user ' . $user->name . '(' . $user->id . ')' . PHP_EOL;

        if (!$user) {
            throw new \Exception('User không tồn tại');
        }

        $commission = $user->getTotalCommissionForUpLevel();

        echo 'Tổng point user ' . $commission . PHP_EOL;

        $commission = $commission * 2;


        if ($user->childs) {
            $child_ids = $user->childs->lists('use_id');
            var_dump($child_ids);

            echo 'Tính tổng point của cấp dưới ' . PHP_EOL;

            /**
             * @var $child Users
             */
            foreach ($user->childs as $child) {
                $commission += $child->getTotalCommissionForUpLevel();
            }
            
        }

        echo 'Tổng point là ' . $commission . PHP_EOL;
        $user->commission = $commission;

        $user->update();
    }
}