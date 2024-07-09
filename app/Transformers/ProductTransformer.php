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
        $item['min_price_policy'] = [];
        //$item['min_price_policy'] = transformer_item($product->minPricePolicy, new ProductPricePolicyTransformer($product));
        //  [
        //     'price' => (int)($product->minPricePolicy->price ?? 0),
        //     'quantity' => (int)($product->minPricePolicy->quantity ?? 0)
        // ];

        $product->buy_quantity = $product->buy_quantity ?? 1;
		$isSeller = (!intval($product->use_order_id) || (intval($product->use_order_id) > 0 && intval($product->use_order_id) == 1)) ? true : false;
        if ((app('auth')->u_id ?? 0) && app('auth')->use_is_seller && $isSeller) {
            //Mua hàng ưu đãi. Mua bao nhiêu cũng được giá sỉ cao nhất
            //$policies = array_reverse($product->pricePolicies->toArray());
            $policies = $product->pricePolicies->toArray(); // 30/6-2023. chinh lay muc chiet khau nho nhat
            $policy = reset($policies);
            if ($policy) {
                
                $item['is_wholesale'] = 1;

                $price = $item['is_discount'] ? $product->discount_price : $product->price;
                $price = $price - $policy['price'];
                $item['discount_percent'] = round(($policy['price'] / $product->price) * 100, 2);
                $item['is_discount'] = 1;
                $item['discount_price'] = $price;
                $item['discount_price_formatted'] = number_format($price);
            }
        } else {
            if ($product->buy_quantity < ($product->minPricePolicy->quantity ?? 0) && !intval($product->leverPrice)) {
                //Mua lần đầu
                if (!$item['buyed']) {
                    $price_first_policy = $product->minPricePolicy;
                    if ($price_first_policy) {
                        $price = $item['is_discount'] ? $product->discount_price : $product->price;
                        $price = $price - (int)$product->minPricePolicy->price;
                        $item['discount_percent'] = round(((int)$product->minPricePolicy->price / $product->price) * 100, 2);
                        $item['is_discount'] = 1;
                        $item['discount_price'] = $price;
                        $item['discount_price_formatted'] = number_format($price);
                    }
                } else if ((app('auth')->u_id ?? 0) && app('auth')->use_family) {
                    //Check user family (Mua sản phẩm lẻ với giá dùng thử - Mức giá buôn đầu tiên)
                    $price_first_policy = $product->minPricePolicy;
                    if ($price_first_policy) {
                        $price = $item['is_discount'] ? $product->discount_price : $product->price;
                        $price = $price - (int)$product->minPricePolicy->price;
                        $item['discount_percent'] = round(((int)$product->minPricePolicy->price / $product->price) * 100, 2);
                        $item['is_discount'] = 1;
                        $item['discount_price'] = $price;
                        $item['discount_price_formatted'] = number_format($price);
                    }
                } elseif ($product->is_wholesale) {
                    $price_first_policy = $product->minPricePolicy;
                    if ($price_first_policy) {
                        $price = $item['is_discount'] ? $product->discount_price : $product->price;
                        $price = $price - (int)$product->minPricePolicy->price;
                        $item['discount_percent'] = round(((int)$product->minPricePolicy->price / $product->price) * 100, 2);
                        $item['is_discount'] = 1;
                        $item['discount_price'] = $price;
                        $item['discount_price_formatted'] = number_format($price);
                    }
                }
            } else {
                if ($product->pricePolicies) {
                    $item['is_wholesale'] = 1;
                    //Check chính sách giá để gán lại giá sản phẩm
                    //Gán vào giảm giá
                    $v_arr_leverPrice = [];
                    //$policies = array_reverse($product->pricePolicies->toArray());// 30/6-2023. chinh lay muc chiet khau nho nhat
                    $policies = $product->pricePolicies->toArray();
                    $v_arr_result = reset($policies); // Lay phan tu dau tien cua mang
					/*$v_arr_policy = [];
                    foreach ($policies as $policy) {
                        if (intval($policy['quantity']) > intval($product->buy_quantity)) {
                            continue;
                        }
                        $v_arr_policy = $policy;
                        break;
                    }

                    
                    if(intval($product->leverPrice) > 0){
                        $policies = php_multisort($policies, array(array('key'=>'quantity','sort'=>'asc')));
                        $policies = array_values($policies);
                        if(intval($product->leverPrice) >count($policies)){
                            $v_arr_leverPrice = end($policies);
                        }else{
                            $v_arr_leverPrice = $policies[intval($product->leverPrice)-1];
                        }
                    }
                    $v_arr_result = [];
                    if(check_array($v_arr_policy) && check_array($v_arr_leverPrice)){
                        if($v_arr_policy['price'] > $v_arr_leverPrice['price']){
                            $v_arr_result = $v_arr_policy;
                        }else{
                            $v_arr_result = $v_arr_leverPrice;
                        }
                    }elseif(check_array($v_arr_policy)){
                        $v_arr_result = $v_arr_policy;
                    }elseif(check_array($v_arr_leverPrice)){
                        $v_arr_result = $v_arr_leverPrice;
                    }*/
                    
                    if(check_array($v_arr_result)){
                        $price = $item['is_discount'] ? $product->discount_price : $product->price;
                        $price = $price - $v_arr_result['price'];
                        $item['discount_percent'] = round((($item['price']-$price) / $item['price']) * 100, 2);
                        $item['is_discount'] = 1;
                        $item['discount_price'] = $price;
                        $item['discount_price_formatted'] = number_format($price);
                    }
                }
            }
        }

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
