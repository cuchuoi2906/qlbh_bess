<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/14/19
 * Time: 03:06
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class ProductPricePolicyTransformer extends TransformerAbstract
{

    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function transform($item)
    {

        $product_price = $this->product->discount_price ? $this->product->discount_price : $this->product->price;
        
        $price = round($product_price - $item->price);
        if($product_price > 0){
            $discount_percent = ($item->price / $product_price) * 100;
        }
        return [
            'quantity' => $item->quantity,
            'price' => $item->price, //price này đã đổi thành số %
            'real_price' => $price,
            'display_real_price' => number_format($price),
            'discount_percent' => round($discount_percent, 2),
            'discount_percent_round' => round($discount_percent),
        ];
    }

}