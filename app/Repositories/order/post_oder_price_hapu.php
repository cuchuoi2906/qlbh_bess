<?php

use App\Models\Order;
use App\Models\ProductHapu;
use App\Models\OrderLog;
use App\Models\OrderProduct;
use App\Models\UserMoneyLog;
use App\Models\Users\UserCart;
use App\Models\Users\Users;
use App\Models\Setting;
use App\Workers\TotalTeamPointDayWorker;
use AppView\Helpers\BaoKimAPI;
use VatGia\Queue\Facade\Queue;

$vars = [];
$orderProduct = OrderProduct::where('orp_ord_id =' . input('order_id'))
    ->all();

if(!input('order_id')){
    throw new RuntimeException("Có lỗi xảy ra!");
}
if($orderProduct){
    $updatePrice = getValue('updatePrice', 'int', 'GET', 0, 0);
    $arr_order_product = [];
    foreach($orderProduct as $item){
        $order_id = getValue('order_id', 'int', 'POST', 0, 0);
        $price_hapu = str_replace(',','',getValue('txt_price_hapu'.$item['orp_id'], 'str', 'POST', "", 0));
        $item['orp_id'];
        if(!$item['orp_quantity']){
            continue;
        }
        if($price_hapu == '' && !$updatePrice){
            throw new RuntimeException("Bạn phải nhập đầy đủ giá tốt nhất");
        }
        $price_hapu = intval($price_hapu);
        $arr_order_product[$item['orp_id']]['orp_supplier_hapu'] = getValue('txt_supplier_hapu'.$item['orp_id'], 'str', 'POST', '', 0);
        $arr_order_product[$item['orp_id']]['orp_price_hapu'] = $price_hapu;
        $arr_order_product[$item['orp_id']]['orp_pharmacy_hapu'] = getValue('txt_pharmacy_hapu'.$item['orp_id'], 'str', 'POST', '', 0);
        $arr_order_product[$item['orp_id']]['orp_note_hapu'] = getValue('note_hapu'.$item['orp_id'], 'str', 'POST', '', 0);
        $arr_order_product[$item['orp_id']]['orp_product_id'] = $item['orp_product_id'];
    }
    if(check_array($arr_order_product)){
        //pre($arr_order_product);die;
        foreach($arr_order_product as $key=>$items){
            \App\Models\OrderProduct::update([
                'orp_supplier_hapu' => $items['orp_supplier_hapu'],
                'orp_price_hapu' => $items['orp_price_hapu'],
                'orp_pharmacy_hapu' => strtoupper($items['orp_pharmacy_hapu']),
                'orp_note_hapu' => $items['orp_note_hapu'],
            ], 'orp_id = ' . $key);
            
            $productHapu = ProductHapu::where('pro_ha_product_id', $items['orp_product_id'])->first();
            if($productHapu){ // Update
                \App\Models\ProductHapu::update([
                    'pro_ha_supplier' => $items['orp_supplier_hapu'],
                    'pro_ha_price' => $items['orp_price_hapu'],
                    'pro_ha_pharmacy' => strtoupper($items['orp_pharmacy_hapu']),
                    'pro_ha_note' => $items['orp_note_hapu'],
                    'pro_ha_price_min' => (($items['orp_price_hapu'] < $productHapu->pro_ha_price_min || !$productHapu->pro_ha_price_min) && $items['orp_price_hapu'] > 0) ? $items['orp_price_hapu'] : $productHapu->pro_ha_price_min,
                    'pro_ha_pharmacy_min' => (($items['orp_price_hapu'] < $productHapu->pro_ha_price_min || !$productHapu->pro_ha_pharmacy_max) && $items['orp_price_hapu'] > 0) ? strtoupper($items['orp_pharmacy_hapu']) : $productHapu->pro_ha_pharmacy_min,
                    'pro_ha_price_max' => (($items['orp_price_hapu'] > $productHapu->pro_ha_price_max || !$productHapu->pro_ha_price_max) && $items['orp_price_hapu'] > 0) ? $items['orp_price_hapu'] : $productHapu->pro_ha_price_max,
                    'pro_ha_pharmacy_max' => (($items['orp_price_hapu'] > $productHapu->pro_ha_price_max || !$productHapu->pro_ha_pharmacy_max) && $items['orp_price_hapu'] > 0) ? strtoupper($items['orp_pharmacy_hapu']) : $productHapu->pro_ha_pharmacy_max,
                ], 'pro_ha_product_id = ' . $items['orp_product_id']);
            }else{
                ProductHapu::insert([
                    'pro_ha_product_id' => $items['orp_product_id'],
                    'pro_ha_supplier' => $items['orp_supplier_hapu'],
                    'pro_ha_price' => $items['orp_price_hapu'],
                    'pro_ha_pharmacy' => strtoupper($items['orp_pharmacy_hapu']),
                    'pro_ha_note' => $items['orp_note_hapu'],
                    'pro_ha_price_min' => $items['orp_price_hapu'],
                    'pro_ha_pharmacy_min' => strtoupper($items['orp_pharmacy_hapu']),
                    'pro_ha_price_max' => $items['orp_price_hapu'],
                    'pro_ha_pharmacy_max' => strtoupper($items['orp_pharmacy_hapu']),
                ]);
            }
        }
        if(!$updatePrice){
            \App\Models\Order::update([
                'ord_status_process' => 1,
            ], 'ord_id = ' . input('order_id'));
        }
        $vars['order_id'] = input('order_id');
        
    }
    return [
        'vars' => $vars
    ];
}