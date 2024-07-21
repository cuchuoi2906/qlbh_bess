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
        'address'
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
        $products = $item->products;
        $leverPrice = 0;
        /*
        // lặp giỏ hảng tính mix giá
        foreach ($products as $product) {
            $product->leverPrice = 0;
            $total_money_percent_wholesale += $product->price * $product->quantity;
        }
        $configData = Setting::where('swe_key', 'LIKE', 'tong_gia_tri_don_hang_huong_uu_dai_%')->select_all();
        $arrconfigData = array();
        foreach($configData as $item1){
            $arrconfigData[] = $item1->swe_value_vn;
        }
        /*if($total_money_percent_wholesale > 0 && $total_money_percent_wholesale >= $arrconfigData[count($arrconfigData)-1]){
            $leverPrice = count($arrconfigData);
        }else{
            for($i=0;$i<count($arrconfigData);$i++){
                if($total_money_percent_wholesale > 0 && $total_money_percent_wholesale >= $arrconfigData[$i] && $total_money_percent_wholesale < $arrconfigData[$i+1]){
                    $leverPrice = $i+1;
                }
            }
        }*/
        $total_point = 0;
        $total_product = 0;
        $total_money = 0;
        $total_money_origin = 0;
        $total_discount = 0;
        foreach ($products as $product) {
            $total_product += $product->quantity;
            $product->info->buy_quantity = $product->quantity;
            $product->info->leverPrice = $leverPrice;
            $product->info->use_order_id = intval($item->user->use_id); // User ID theo Đơn hàng
            $product->info->use_is_seller = intval($item->user->use_is_seller); // User ID theo Đơn hàng
            $productInfo = transformer_item($product->info, new ProductTransformer());
            $total_point += $productInfo['point'] * $product->quantity;
            
            $price = $productInfo['is_discount'] ? $productInfo['discount_price'] : $productInfo['price'];
            $total_money += $price * $product->quantity;
            $total_money_origin += $productInfo['price'] * $product->quantity;
            //echo $productInfo['price'].'__'.$price;
            $total_discount += ($productInfo['price'] * $product->quantity - $price * $product->quantity);
        }

        return [
            'id' => (int)$item->id,
            'code' => $item->code,
//            'link' => url('order.detail', ['code' => $item->code]),
            'amount' => $item->amount,
            'amount_formatted' => number_format($item->amount),
            'status' => $status[$item->status_code],
            'payment_type' => Order::paymentTypes()[$item->payment_type] ?? 'COD',
            'commission_type' => $item->commission_type == 2 ? 2 : 1,
            'created_at' => new \DateTime($item->created_at),
            'commission' => $item->getCommission(),
            'shipping_fee' => (int)$item->auto_shipping_fee,
            'shipping_fee_display' => number_format($item->auto_shipping_fee),
            'total_product' => number_format((int)$total_product),
            'total_point' => $total_point,
            'note' => $item->note,
            'total_money' => $total_money,
            'total_money_origin' => $total_money_origin,
            'total_discount' => $total_discount,
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

}