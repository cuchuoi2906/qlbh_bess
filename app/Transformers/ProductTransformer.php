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

class ProductTransformer extends TransformerAbstract {


    public $availableIncludes = [
        'images',
        'avatar',
        'category',
        'pricePolicies',
        'topRacing'
    ];

    public $defaultIncludes = [
        'avatar',
        //'images',
        //'category'
    ];

    /**
     * @param Product $product
     * @return array
     */
    public function transform($product) {
        if (!$product || !($product->id ?? 0)) {
            return [];
        }
        
        $item = [
            'id' => (int)$product->id,
            'name' => $product->name,
            'barcode' => $product->barcode,
            'code' => $product->code,
            'rewrite' => removeTitle($product->name),
            'teaser' => nl2br($product->teaser),
            'specifications' => '<div>' . html_entity_decode($product->functions) . '</div>',
            'functions' => html_entity_decode($product->functions),
            'price' => (int)$product->price,
            'discount_price' => (int)$product->discount_price,
            'is_discount' => $product->discount_price ? 1 : 0,
            'discount_percent' => (int)($product->discount_price && $product->price ? ((($product->price - $product->discount_price) / $product->price) * 100) : 0),
            'is_hot' => (int)$product->is_hot,
            'quantity' => (int)$product->quantity,
            'commission' => (int)$product->commission, //Tổng commission
            'paid_commission' => (int)$product->commission,
            'price_formatted' => number_format($product->price),
            'discount_price_formatted' => number_format($product->discount_price),
            'created_at' => new \DateTime($product->created_at),
            'buyed' => (bool)$product->buyed(),
            'total_liked' => (int)$product->total_liked,
            'liked' => $product->liked(),
            'buy_quantity' => (int)($product->buy_quantity ?? 0),
            'is_wholesale' => 0,
            'point' => (int)$product->point,
            'db_price' => (int)$product->price,
            'db_discount_price' => (int)$product->discount_price,
            'active_inventory' => (int)$product->active_inventory,
            'video_url' => ($product->pro_video_file_name != '') ? url() . '/upload/products/' . $product->pro_video_file_name : '',
        ];
        $price_policy = transformer_item($product->minPricePolicy, new ProductPricePolicyTransformer($product));
        $item['price_policy'] = 0;
        if ($product->pricePolicies) {
            //Check chính sách giá để gán lại giá sản phẩm
            //Gán vào giảm giá
            $v_arr_leverPrice = [];
            $policies = array_reverse($product->pricePolicies->toArray());// 30/6-2023. chinh lay muc chiet khau nho nhat
            $v_policy_price = 0;
            foreach ($policies as $policy) {
                if (intval($product->buy_quantity) >= intval($policy['quantity'])) {
                    $v_policy_price = $policy['price'];
                    break;
                }
            }
            $item['price_policy'] = intval($v_policy_price);
        }
        //  [
        //     'price' => (int)($product->minPricePolicy->price ?? 0),
        //     'quantity' => (int)($product->minPricePolicy->quantity ?? 0)
        // ];
        $item['is_discount'] = $product->discount_price ? 1: 0;

        $product->buy_quantity = $product->buy_quantity ?? 1;

        return $item;
    }

    public function includeImages($product) {
        $images = $product->images ?? collect([]);

        return $this->collection($images, new ProductImageTransformer());
    }

    public function includeAvatar($product) {
        $avatar = $product->avatar ?? collect([]);

        return $this->item($avatar, new ProductImageTransformer());
    }

    public function includeCategory($product) {
        return $this->item($product->category, new CategoryTransformer());
    }

    public function includePricePolicies($product) {
        $policies = $product->pricePolicies ?? collect([]);

        return $this->collection($policies, new ProductPricePolicyTransformer($product));
    }

    public function includeTopRacing($product) {
        $campaigns = $product->topRacing ?? collect([]);
        return $this->collection($campaigns, new TopRacingCampaignTransformer());
    }
}
