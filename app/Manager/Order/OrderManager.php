<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-12
 * Time: 15:45
 */

namespace App\Manager\Order;

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderProduct;
use App\Models\UserMoneyLog;
use App\Models\Users\Users;


class OrderManager
{
    protected $failStatuses = [Order::SUCCESS, Order::PAID];
    protected $failPaymentStatuses = [Order::PAYMENT_STATUS_SUCCESS];

    /**
     * @param Order $order
     * @param Users $users
     * @return Order|mixed
     */
    public function paidOrder(Order $order, Users $user)
    {
        if ($order->user_id != $user->id) {
            throw new \Exception("Owner order fail");
        }
        //Kiểm tra trạng thái order
        if (in_array($order->status_code, $this->failStatuses) ||
            in_array($order->payment_status, $this->failPaymentStatuses)) {
            throw new \Exception("Trạng thái đơn hàng không đúng");
        }
        //Kiểm tra tiền user
        if ($user->money_add < $order->amount) {
            throw new \Exception("Không đủ tiền thanh toán");
        }

        //Ghi Logs order
        $orderLogId = OrderLog::insert([
            'orl_ord_id' => $order->id,
            'orl_old_status_code' => $order->status_code,
            'orl_new_status_code' => $order->status_code,
            'orl_old_payment_status' => $order->payment_status,
            'orl_new_payment_status' => Order::PAYMENT_STATUS_SUCCESS,
        ]);

        //Thay đổi trạng thái orders;
        Order::update(['ord_payment_status' => Order::PAYMENT_STATUS_SUCCESS], 'ord_id = ' . $order->id);


        //Ghi Log Money
        $current = $user->money_add - $order->amount;
        $note = "Thanh toán hóa đơn " . $order->code;
        $logMoneyId = UserMoneyLog::insert([
            'uml_user_id' => $user->id,
            'uml_amount' => -($order->amount),
            'uml_current' => $current,
            'uml_type' => UserMoneyLog::TYPE_MONEY_ADD,
            'uml_note' => $note
        ]);

        //Trừ tiền tài khoản money
        Users::update(['use_money_add' => $current], 'use_id = ' . $user->id);


        return Order::findByID($order->id);
    }

    public static function commissions($order_id)
    {
        $order = Order::findByID($order_id);
        if (!$order) {
            throw new \RuntimeException('Đơn hàng không tồn tại', 404);
        }

        $user = $order->user;

        $products = $order->products;

        $total_product = 0;
        $total_money = 0;
        $total_direct_commission = 0;
        $total_commission = 0;
        $total_point = 0;

        foreach ($products as $product) {

            $quantity = $product->quantity;
            $product->info->buy_quantity = $product->quantity;
            $product = transformer_item($product->info, new \App\Transformers\ProductTransformer());
            $product = collect_recursive($product);


            $total_product += $quantity;
            $price = $product->discount_price ? $product->discount_price : $product->price;

            $total_money += $price * $quantity;

            $direct_commission = 0;
            $min_price_policy = 0;
            if ($product->discount_price) {
                $direct_commission = ($product->price - $product->discount_price) * $quantity;
                $total_direct_commission += $direct_commission;
                $min_price_policy = (int)$product->min_price_policy->price * $quantity;
            }

            $commission = $product->paid_commission * $quantity;

            $commission_sale_price = $commission - $direct_commission + $min_price_policy;
            $commission_sale_price = $commission_sale_price > 0 ? $commission_sale_price : 0;
            $total_commission += $commission_sale_price;
            $total_point += (int)$product->point * $quantity;

            OrderProduct::where('orp_ord_id', $order->id)->where('orp_product_id', $product->id)
                ->update([
                    'orp_price' => $product->price,
                    'orp_sale_price' => ($order->commission_type == 2) ? $product->price : $price,
                    'orp_commit_current' => $commission_sale_price
                ]);

        }

        if ($order->commission_type == 2) {
            $total_money += $total_direct_commission;
        }

        //Update tổng số tiền vào đơn hàng
        $order->amount = (int)$total_money;
        $order->update();

        /**
         * Lưu commission
         */
        \App\Models\OrderCommission::where('orc_order_id', $order->id)->delete();

        //Get commission events
        $event_direct_commission_ratio = 0;
        $event_parent_commission_ratio = 0;
        $event = \App\Models\Event::where('evt_active', '>', 0)
            ->where('evt_start_time', '<', time())
            ->where('evt_end_time', '>', time())
            ->first();
        if ($event) {
            $event_direct_commission_ratio = $event->direct_commission_ratio;
            $event_parent_commission_ratio = $event->parent_commission_ratio;
        }
        //Commission trực tiếp
        $total_direct_commission = (int)($total_direct_commission / 1000) * 1000;

        $add_total_direct_commission = $total_direct_commission;
        $direct_vat = 0;
        if ($order->commission_type == 2) {
            $direct_vat = $total_direct_commission / 10;
            $direct_vat = (int)($direct_vat / 100) * 100;
            $add_total_direct_commission = $total_direct_commission - $direct_vat;
        }

        \App\Models\OrderCommission::insert([
            'orc_order_id' => $order_id,
            'orc_user_id' => $user->id,
            'orc_status_code' => (($order->commission_type == 2) ? \App\Models\OrderCommission::STATUS_NEW : \App\Models\OrderCommission::STATUS_SUCCESS),
            'orc_amount' => $add_total_direct_commission, //Làm tròn đến 100đ
            'orc_is_owner' => 1,
            'orc_is_direct' => 1,
            'orc_type' => $order->commission_type == 2 ? 0 : 1,
            'orc_vat' => $direct_vat,
            'orc_point' => 0
        ]);

        //Event direct commission
        if ($event_direct_commission_ratio > 1) {
            $event_direct_commission = ($event_direct_commission_ratio * $total_direct_commission) - $total_direct_commission;
            \App\Models\OrderCommission::insert([
                'orc_order_id' => $order_id,
                'orc_user_id' => $user->id,
                'orc_status_code' => \App\Models\OrderCommission::STATUS_SUCCESS,
                'orc_amount' => $event_direct_commission,
                'orc_is_owner' => 1,
                'orc_is_direct' => 1,
                'orc_type' => 1,
                'orc_vat' => 0,
                'orc_point' => 0,
                'orc_event_id' => (int)$event->id
            ]);
        }

        $commission_ratio = (int)setting('commission_ratio', 2);
        $commission_ratio = 1 / $commission_ratio;

        //Commission hệ thống
        $parent = $user;

        while ($parent && $total_commission > 0) {
            $user_commission_percent = setting('user_level_' . $parent->level . '_commission');
            if (!$user_commission_percent || $user_commission_percent >= 100) {
                $parent = $parent->parent;
                continue;
            }
            $user_commission = intval($user_commission_percent / 100 * $total_commission / 100) * 100;
            if ($user_commission >= 100) {

                $add_user_vat = $user_commission / 10;
                $add_user_vat = (int)($add_user_vat / 100) * 100;
                $add_user_commission = $user_commission - $add_user_vat;
                //Lưu user commssion
                \App\Models\OrderCommission::insert([
                    'orc_order_id' => $order_id,
                    'orc_user_id' => $parent->id,
                    'orc_status_code' => \App\Models\OrderCommission::STATUS_NEW,
                    'orc_amount' => $add_user_commission,
                    'orc_vat' => $add_user_vat,
                    'orc_is_owner' => ($parent->id == $user->id) ? 1 : 0,
                    'orc_point' => ($parent->id == $user->id) ? $total_point : 0,
                ]);

                if ($parent->id == $user->id) {
                    if ($event_direct_commission_ratio > 1) {
                        $user_commission = ($event_direct_commission_ratio * $user_commission) - $user_commission;
                        \App\Models\OrderCommission::insert([
                            'orc_order_id' => $order_id,
                            'orc_user_id' => $user->id,
                            'orc_status_code' => \App\Models\OrderCommission::STATUS_SUCCESS,
                            'orc_amount' => $user_commission,
                            'orc_is_owner' => 1,
                            'orc_type' => 1,
                            'orc_point' => $total_point * $event_direct_commission_ratio,
                            'orc_event_id' => (int)$event->id
                        ]);
                    }
                } else {
                    if ($event_parent_commission_ratio > 1) {
                        $event_parent_commission = ($event_parent_commission_ratio * $user_commission) - $user_commission;
                        \App\Models\OrderCommission::insert([
                            'orc_order_id' => $order_id,
                            'orc_user_id' => $parent->id,
                            'orc_status_code' => \App\Models\OrderCommission::STATUS_SUCCESS,
                            'orc_amount' => $event_parent_commission,
                            'orc_is_owner' => 0,
                            'orc_type' => 1,
                            'orc_point' => 0,
                            'orc_event_id' => (int)$event->id
                        ]);
                    }
                }


                $total_commission -= $user_commission;
            }
            $parent = $parent->parent;
        }

        \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\OrderProductCommissionDetailWorker::$name, \App\Workers\OrderProductCommissionDetailWorker::class, [
            'order_id' => $order_id
        ]);
    }

}