<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 12/8/19
 * Time: 20:37
 */

namespace App\Workers;


use App\Models\Order;
use App\Models\OrderProductCommission;
use App\Transformers\ProductTransformer;

class OrderProductCommissionDetailWorker
{

    public static $name = 'order_product_commission';

    public function fire($data)
    {
        $order_id = $data['order_id'] ?? 0;
        $order = Order::findByID($order_id);
        if ($order) {
            echo 'Tính hoa hồng chi tiết từng sản phẩm của đơn hàng ' . $order->id . PHP_EOL;

//            $check = OrderProductCommission::where('opc_order_id', $order->id)->first();
//            if ($check) {
//                echo 'Đã tính hoa hồng cho đơn hàng này rồi' . PHP_EOL;
//                return;
//            }

            OrderProductCommission::where('opc_order_id', $order->id)->delete();

            foreach ($order->products as $product) {

                $product->info->buy_quantity = $product->quantity;
                $product_info = transformer_item($product->info, new ProductTransformer());
                $product_info = collect_recursive($product_info);

                echo 'Tính hoa hồng cho sản phẩm ' . $product_info->id . ' ' . $product_info->name . PHP_EOL;

                if ($product_info->is_discount) {
                    $direct_commission = ($product_info->price - $product_info->discount_price) * $product->quantity;
                    $commission = $product_info->paid_commission * $product->quantity - $direct_commission;
                } else {
                    $direct_commission = 0;
                    $commission = $product_info->paid_commission * $product->quantity - $direct_commission;
                }

                //Insert direct commission
                if ($direct_commission) {
                    $vat = 0;
                    if ($order->commission_type == 2) {
                        $vat = $direct_commission / 10;
                        $direct_commission = $direct_commission - $vat;
                    }

                    OrderProductCommission::insert([
                        'opc_order_id' => $order->id,
                        'opc_user_id' => $order->user_id,
                        'opc_product_id' => $product_info->id,
                        'opc_quantity' => $product->quantity,
                        'opc_commission' => $direct_commission,
                        'opc_vat' => $vat,
                        'opc_is_owner' => 1,
                        'opc_is_direct' => 1,
                        'opc_type' => $order->commission_type == 2 ? 0 : 1
                    ]);
                }

                $parent = $order->user;
                while ($parent && $commission > 0) {

                    $user_commission_percent = setting('user_level_' . $parent->level . '_commission');
                    if (!$user_commission_percent || $user_commission_percent >= 100) {
                        $parent = $parent->parent;
                        continue;
                    }
                    $user_commission = intval($user_commission_percent / 100 * $commission / 100) * 100;

                    if ($user_commission >= 100) {
                        $add_user_commission_vat = $user_commission / 10;
                        $add_user_commission = $user_commission - $add_user_commission_vat;

                        //Lưu user commssion
                        \App\Models\OrderProductCommission::insert([
                            'opc_order_id' => $order->id,
                            'opc_user_id' => $parent->id,
                            'opc_product_id' => $product_info->id,
                            'opc_quantity' => $product->quantity,
                            'opc_commission' => $add_user_commission,
                            'opc_vat' => $add_user_commission_vat,
                            'opc_is_owner' => ($parent->id == $order->user->id) ? 1 : 0,
                        ]);

                        $commission -= $user_commission;
                    }
                    $parent = $parent->parent;
                }
            }
        }
    }
}