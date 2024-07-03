<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/23/19
 * Time: 01:36
 */

namespace AppView\Controllers\Api;


use App\Models\Users\Users;

class ReportController extends ApiController
{

    /**
     * Lấy báo cáo thông kê các chỉ số
     * - Số thành viên
     * - Số thành viên trực tiếp
     * - Doanh số thành công / Tổng
     * - Đơn hàng thành công / Tổng
     */
    public function getIndex()
    {
        $members = model('statistic/member')->load([
            'user_id' => app('auth')->u_id
        ]);

        $money = model('statistic/money')->load([
            'user_id' => app('auth')->u_id
        ]);

        $order = model('statistic/order')->load([
            'user_id' => app('auth')->u_id
        ]);

        return [
            'member' => $members['vars'],
            'money' => $money['vars'],
            'order' => $order['vars']
        ];
    }

    public function getMoney()
    {
        //Cá nhân toàn thời gian
        $commission_owner = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'type' => 1,
        ]);
        //Cá nhân 10 ngày gần nhất
        $commission_owner_10 = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'type' => 1,
            'start_time' => date('Y-m-d', strtotime('-10 days'))
        ]);
        //Cá nhân 1 tháng gần nhất
        $commission_owner_30 = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'type' => 1,
            'start_time' => date('Y-m-d', strtotime('-1 months'))
        ]);

        //Trực tiếp toàn thời gian
        $commission_direct = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'type' => 2,
        ]);
        //Trực tiếp 10 ngày gần nhất
        $commission_direct_10 = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'type' => 2,
            'start_time' => date('Y-m-d', strtotime('-10 days'))
        ]);
        //Trực tiếp 1 tháng gần nhất
        $commission_direct_30 = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'type' => 2,
            'start_time' => date('Y-m-d', strtotime('-1 months'))
        ]);

        //Hệ thống toàn thời gian
        $commission = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
        ]);
        //Hệ thống 10 ngày gần nhất
        $commission_10 = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'start_time' => date('Y-m-d', strtotime('-10 days'))
        ]);
        //Hệ thống  1 tháng gần nhất
        $commission_30 = repository('statistic/commission')->get([
            'user_id' => app('auth')->u_id,
            'start_time' => date('Y-m-d', strtotime('-1 months'))
        ]);

        //Đơn hàng
        $order_total = repository('statistic/order')->get([
            'user_id' => app('auth')->u_id
        ]);
        //10 ngày
        $order_10 = repository('statistic/order')->get([
            'user_id' => app('auth')->u_id,
            'start_time' => date('Y-m-d', strtotime('-10 days'))
        ]);
        //1 tháng
        $order_30 = repository('statistic/order')->get([
            'user_id' => app('auth')->u_id,
            'start_time' => date('Y-m-d', strtotime('-1 months'))
        ]);

        /**
         * @var $user Users
         */
        $user = Users::findByID(app('auth')->u_id);

        return [
            'commission' => [
                'owner' => [
                    'total' => (int)$commission_owner['vars'],
                    'last_10_days' => (int)$commission_owner_10['vars'],
                    'last_month' => (int)$commission_owner_30['vars'],
                ],
                'direct' => [
                    'total' => (int)$commission_direct['vars'],
                    'last_10_days' => (int)$commission_direct_10['vars'],
                    'last_month' => (int)$commission_direct_30['vars'],
                ],
                'system' => [
                    'total' => (int)$commission['vars'],
                    'last_10_days' => (int)$commission_10['vars'],
                    'last_month' => (int)$commission_30['vars'],
                ]
            ],
            'order' => [
                'total' => (int)$order_total['vars']['success'],
                'last_10_days' => (int)$order_10['vars']['success'],
                'last_month' => (int)$order_30['vars']['success'],
            ],
            'point' => [
                'total' => (int)$user->getTotalCommissionForUpLevel(),
                'last_10_days' => (int)$order_10['vars']['success'],
                'last_month' => (int)$order_30['vars']['success'],
            ]
        ];

    }

    public function getAll()
    {
        //Doanh thu
        //Ca nhan
        //Đơn hàng
        $order_total = repository('statistic/owner_order')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);
        $order_direct = repository('statistic/direct_order')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);
        $order_all = repository('statistic/all_order')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);
        //Tich luy
        $owner_commission = repository('statistic/owner_commission')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);

        $direct_commission = repository('statistic/direct_commission')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);

        $all_commission = repository('statistic/all_commission')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);

        //Users
        $users = repository('statistic/all_member')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);
        
        //Users
        $users_point = repository('statistic/point_group_member')->get([
                'user_id' => app('auth')->u_id
            ] + $this->input);
        
        return [
            'order' => [
                'owner' => $order_total['vars'],
                'direct' => $order_direct['vars'],
                'all' => $order_all['vars'],
            ],
            'commission' => [
                'owner' => $owner_commission['vars'],
                'direct' => $direct_commission['vars'],
                'all' => $all_commission['vars'],
            ],
            'point'=>$users_point['vars'],
            'users' => $users['vars']
        ];
    }
}