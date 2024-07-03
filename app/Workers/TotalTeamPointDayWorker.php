<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/23/20
 * Time: 10:07
 */

namespace App\Workers;


use App\Models\BestTeam;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Users\Users;

class TotalTeamPointDayWorker
{

    public static $name = 'total_team_point_day';

    public function fire($data)
    {

        /**
         * @var $user Users
         */
        $user_id = $data['user_id'] ?? 0;
        $user = Users::findByID($user_id);

        if (!$user) {
            echo 'Không tồn tại user id ' . $user_id . PHP_EOL;
            return;
        }

        $order_id = $data['order_id'] ?? 0;
        $order = Order::findByID($order_id);
        if (!$order) {
            echo 'Đơn ' . $order_id . ' không tồn tại' . PHP_EOL;
            return;
        }

        //Team Commission
        $date = new \DateTime($order->created_at);
        $point = $user->getTotalCommissionForUpLevel($date->format('Y-m-d'));
        $point = $point * 2;
        /**
         * @var $child Users
         */
        foreach ($user->childs ?? [] as $child) {
            $point += $child->getTotalCommissionForUpLevel($date->format('Y-m-d'));
        }

        BestTeam::where('bes_date', $date->format('Y-m-d'))
            ->where('bes_user_id', (int)$user->id)
            ->delete();
            
        echo 'Update commission' . PHP_EOL;
        BestTeam::replace([
            'bes_user_id' => (int)$user->id,
            'bes_date' => $date->format('Y-m-d'),
            'bes_point' => (int)$point,
            'bes_type' => BestTeam::TYPE_TEAM_COMMISSION
        ]);

        //Tổng số lượng sản phẩm cá nhân
        $products_total = OrderProduct::fields('*')
            ->sum('orp_quantity', 'total_quantity')
            ->sum('orp_quantity * orp_sale_price', 'total_money')
            ->inner_join('orders', 'orp_ord_id = ord_id AND ord_status_code <> \'' . Order::CANCEL . '\'')
            ->where('ord_user_id', $user->id)
            ->where('DATE(ord_created_at) = \'' . $date->format('Y-m-d') . '\'')
            ->group_by('orp_product_id')
            ->all();

        //Đang dở
        $teams = [];

        foreach ($products_total as $product) {
            echo 'Update cá nhân sản phẩm ' . $product->product_id . PHP_EOL;
            BestTeam::replace([
                'bes_user_id' => (int)$user->id,
                'bes_date' => $date->format('Y-m-d'),
                'bes_point' => (int)$product->total_quantity,
                'bes_type' => BestTeam::TYPE_OWN_PRODUCT_QUANTITY,
                'bes_product_id' => $product->product_id,
                'bes_money' => $product->total_money,
                'bes_money_point' => $product->total_money,
            ]);

            echo 'Update team sản phẩm ' . $product->product_id . PHP_EOL;
            BestTeam::replace([
                'bes_user_id' => (int)$user->id,
                'bes_date' => $date->format('Y-m-d'),
                'bes_point' => (int)$product->total_quantity,
                'bes_type' => BestTeam::TYPE_TEAM_PRODUCT_QUANTITY,
                'bes_product_id' => $product->product_id,
                'bes_money' => $product->total_money,
                'bes_money_point' => $product->total_money * 2,
            ]);

            if ($user->parent) {

                echo 'Update parent team sản phẩm ' . $product->product_id . PHP_EOL;
                BestTeam::insertUpdate([
                    'bes_user_id' => (int)$user->parent->id,
                    'bes_date' => $date->format('Y-m-d'),
                    'bes_point' => (int)$product->total_quantity,
                    'bes_type' => BestTeam::TYPE_TEAM_PRODUCT_QUANTITY,
                    'bes_product_id' => $product->product_id,
                    'bes_money' => (int)$product->total_money,
                    'bes_money_point' => $product->total_money,
                ], [
                    'bes_point' => db_raw('bes_point + ' . (int)$product->total_quantity),
                    'bes_money' => db_raw('bes_money + ' . (int)$product->total_money),
                    'bes_money_point' => db_raw('bes_money_point + ' . $product->total_money),
                ]);
            }
        }

        //Tính theo team


    }
}