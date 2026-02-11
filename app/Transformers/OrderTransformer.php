<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 01:58
 */

namespace App\Transformers;

use App\Models\Order;
use App\Models\Setting;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'logs',
        'products',
        'user',
        'address',
        'useradminhapu'
    ];

    public $defaultIncludes = [
        'address'
        //'avatar',
        //'images',
        //'category'
    ];

    /**
     * @param Order $item
     * @return array
     */
    public function transform($item)
    {
        $status = [
            Order::NEW => 'Đơn hàng mới',
            Order::PENDING => 'Chờ xử lý',
            Order::BEING_TRANSPORTED => 'Đang vận chuyển',
            Order::SUCCESS => 'Thành công',
            Order::CANCEL => 'Thất bại',
            Order::RECEIVED => 'Đã nhận hàng'
        ];
        $leverPrice = 0;
        $total_point = 0;
        $total_product = 0;
        $total_money = 0;
        $total_money_origin = 0;
        $total_discount = 0;

        return [
            'id' => (int)$item->id,
            'code' => $item->code,
//            'link' => url('order.detail', ['code' => $item->code]),
            'amount' => $item->amount,
            'amount_formatted' => number_format($item->amount),
            'status' => $status[$item->status_code],
            'status_code' => $item->status_code,
            'payment_type' => Order::paymentTypes()[$item->payment_type] ?? 'COD',
            'commission_type' => $item->commission_type == 2 ? 2 : 1,
            'created_at' => new \DateTime($item->created_at),
            'updated_at' => new \DateTime($item->updated_at),
            'commission' => $item->getCommission(),
            'shipping_fee' => (int)$item->auto_shipping_fee,
            'shipping_fee_display' => number_format($item->auto_shipping_fee),
            'total_product' => number_format((int)$total_product),
            'total_point' => $total_point,
            'note' => $item->note,
            'total_money' => 0,
            'total_money_origin' => 0,
            'total_discount' => 0,
            'note' => $item->note,
            'ship_name' => $item->ord_ship_name,
            'ship_phone' => $item->ord_ship_phone,
            'shipping_car' => $item->ord_shipping_car,
            'shipping_car_start' => $item->ord_shipping_car_start,
            'status_process' => $item->ord_status_process,
            'stock_check_status' => $item->ord_stock_check_status,
        ];
    }

    public function includeLogs($item)
    {
        return $this->collection($item->logs, new OrderLogTransformer());
    }

    public function includeProducts($item)
    {
        $products = $item->products ?? collect([]);

        return $this->collection($products, new OrderProductTransformer());
    }

    public function includeUser($item)
    {
        return $this->item($item->user, new UserTransformer());
    }

    public function includeAddress($item)
    {
        return $this->item($item->address ?? collect([]), new UserAddressTransformer());
    }
    
    public function includeUserAdminHapu($item)
    {
        return $this->item($item->userAdminHapu ?? collect([]), new UserAdminHapuTransformer());
    }
}