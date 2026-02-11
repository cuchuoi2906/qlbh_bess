<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/12/18
 * Time: 01:14
 */

namespace App\Transformers;


use App\Models\Product;
use League\Fractal\TransformerAbstract;

class OrderProductTransformer extends TransformerAbstract
{


    public $defaultIncludes = [
        'avatar',
    ];

    /**
     * @param Product $product
     * @return array
     */
    public function transform($product)
    {
        if (!$product) {
            return [];
        }

        if ($product->sale_price) {
            $total = $product->quantity * $product->sale_price;
        } else {
            $total = $product->quantity * $product->price;
        }
        if($product->productHapu){
            $product->orp_supplier_hapu = $product->orp_supplier_hapu == '' ? $product->productHapu->pro_ha_supplier : $product->orp_supplier_hapu;
            //$product->orp_price_hapu = $product->orp_price_hapu == 0 ? $product->productHapu->pro_ha_price : $product->orp_price_hapu;
            $product->orp_pharmacy_hapu = $product->orp_pharmacy_hapu == '' ? $product->productHapu->pro_ha_pharmacy : $product->orp_pharmacy_hapu;
            $product->orp_note_hapu = $product->orp_note_hapu == '' ? $product->productHapu->pro_ha_note : $product->orp_note_hapu;
        }
        if(isset($_GET['test'])){
			//pre("1");
            pre("  ");
        }

        $item = [
            'id' => (int)$product->id,
            'name' => ($product->info) ? $product->info->name : '',
            'rewrite' => ($product->info) ? removeTitle($product->info->name) : '',
//            'link' => ($product->info) ? url('product.detail', ['rewrite' => removeTitle($product->info->name), 'product_id' => (int)$product->id]) : '#',
            'price' => (float)$product->price,
            'sale_price' => (float)$product->sale_price,
            'quantity' => (int)$product->quantity,
            'price_formatted' => number_format($product->price),
            'sale_price_formatted' => number_format($product->sale_price),
            'total_price_formatted' => number_format($total),
            'discount_percent' => round(100 - (($product->sale_price / $product->price) * 100), 2),
            'discount_price' => (int)$product->discount_price,
            'is_discount' => $product->discount_price ? 1 : 0,
            'supplier_hapu' => $product->orp_supplier_hapu,
            'price_hapu' => $product->orp_price_hapu,
            'pharmacy_hapu' => $product->orp_pharmacy_hapu,
            'note_hapu' => $product->orp_note_hapu,
            'check_hapu_status' => $product->orp_check_hapu_status,
            'check_hapu_note' => $product->orp_check_hapu_note,
            'hapu_status_pick' => $product->orp_hapu_status_pick,
        ];

        return $item;
    }

    public function includeAvatar($product)
    {
        $avatar = $product->info->avatar ?? collect([]);

        return $this->item($avatar, new ProductImageTransformer());
    }


}