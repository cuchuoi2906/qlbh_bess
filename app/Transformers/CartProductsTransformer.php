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

class CartProductsTransformer extends TransformerAbstract
{


    public $availableIncludes = [
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

        $item = [
            'id' => (int)$product->id,
            'name' => $product->name,
            'link' => url('product.detail', ['rewrite' => removeTitle($product->name), 'product_id' => (int)$product->id]),
            'teaser' => nl2br($product->teaser),
            'qty_order' => (int)$product->qty_order,
            'price' => (float)$product->price,
            'discount_price' => (float)$product->discount_price,
            'quantity' => (int)$product->quantity,
            'is_discount' => $product->discount_price ? 1 : 0,
            'price_formatted' => number_format($product->price),
            'discount_price_formatted' => number_format($product->discount_price),
            'created_at' => new \DateTime($product->created_at)
        ];

        return $item;
    }

    public function includeAvatar($product)
    {
        $avatar = $product->avatar ?? collect([]);

        return $this->item($avatar, new ProductImageTransformer());
    }
}