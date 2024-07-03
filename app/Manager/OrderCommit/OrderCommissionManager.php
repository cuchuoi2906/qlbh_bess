<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-08
 * Time: 11:33
 */

namespace App\Manager\OrderCommit;


use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\UserMoneyLog;
use App\Models\Users\Users;
use App\Models\Users\UserWallet;
use App\Workers\TotalTeamPointDayWorker;
use App\Workers\UserTotalPointStatisticWorker;
use AppView\Helpers\Notification;
use VatGia\Queue\Facade\Queue;


class OrderCommissionManager
{
    /**
     * @param Order $order
     * @throws \Exception
     */
    public function processSuccess(Order $order)
    {
        if ($order->status_code == Order::SUCCESS) {
            throw new \Exception("Order was success");
        }
        //Update Order
        Order::update([
            'ord_status_code' => Order::SUCCESS,
            'ord_payment_status' => Order::PAYMENT_STATUS_SUCCESS
        ], 'ord_id = ' . $order->id);

        //Update commission
        $commissions = \App\Models\OrderCommission::where('orc_status_code', OrderCommission::STATUS_NEW)
            ->where('orc_order_id', $order->id)
            ->all();

        $add_comment = '';
        foreach ($commissions as $commission) {
            try {

                try {
                    if ($commission->user_id == $order->user_id) {
                        $add_comment = ' của ' . ($commission->user->name ?? '');
                    }
                } catch (\Exception $e) {

                }
                if(intval($commission->amount > 0)){
                    add_money(
                        $commission->user_id,
                        $commission->amount,
                        UserMoneyLog::TYPE_COMMISSION,
                        'Chúc mừng bạn vừa nhận được ' . number_format($commission->amount) . ' điểm tích lũy từ đơn hàng ' . $order->code . $add_comment,
                        $order->id,
                        UserMoneyLog::SOURCE_TYPE_ORDER
                    );
                }

                $commission->orc_status_code = OrderCommission::STATUS_SUCCESS;
                $commission->update();

                //Check and update level
//                if ($commission->is_owner == 1) {
                $this->checkAndUpdateLevel($commission->user_id);
//                }

                Queue::pushOn(UserTotalPointStatisticWorker::$name, UserTotalPointStatisticWorker::class, [
                    'user_id' => $commission->user_id,
                    'commission' => (int)$commission->amount
                ]);

                Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
                    'user_id' => $commission->user_id,
                    'order_id' => $order->id
                ]);

            } catch (\Exception $e) {

            }

        }

    }

    /**
     * Update lv Commission
     * => phải sum các bản ghi vì Hoa hồng có thể rút hoặc chuyển ví nên không thể xét commission hiện tại của users
     * @param $user
     */
    public function checkAndUpdateLevel($user_id)
    {

        /**
         * @var $user Users
         */
        $user = Users::findByID($user_id);

        if ($user->level >= Users::MAX_LEVEL) return;

        $amount = $user->getTotalCommissionForUpLevel();


        //Lấy settings ngưỡng tăng cấp để check
        $thresholds = [];
        for ($level = $user->level + 1; $level <= Users::MAX_LEVEL; $level++) {
            $thresholds[$level] = (int)setting('user_up_level_' . $level . '_threshold', 0);
        }

        $current_level = $user->level;
        if (!empty($thresholds)) {
            //Check ngược từ level cao đến level thấp
//            $thresholds = array_reverse($thresholds);

            foreach ($thresholds as $level => $threshold) {
                if ($threshold > 0 && $amount >= $threshold) {
                    $user->level = $level;
                }
            }
            if ($current_level < $user->level) {
                $user->update();
                Notification::to(
                    $user->id,
                    'Thông báo thay đổi cấp độ user',
                    'Chúc mừng bạn vừa đạt level ' . $user->level . '. Hãy đặt thêm đơn hàng để được hưởng chiết khấu cao hơn nhé.'
                );
            }
        }

    }

}