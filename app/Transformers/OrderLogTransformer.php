<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 02:01
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class OrderLogTransformer extends TransformerAbstract
{

    public function transform($item)
    {
        $status = [
            'NEW' => 'Đơn hàng mới',
            'PENDING' => 'Chờ xử lý',
            'BEING_TRANSPORTED' => 'Đang vận chuyển',
            'SUCCESS' => 'Thành công'
        ];

        return [
            'id' => (int)$item->id,
            'status_code' => $item->orl_new_status_code,
            'order_status' => $status[$item->orl_new_status_code],
        ];
    }

}