<?php
use App\Models\Product;

require_once("../../bootstrap.php");
require_once 'inc_security.php';

$quality = getValue("quality");
$product_id = getValue("product_id");

//Lấy danh sách sản phẩm
$result = model('products/get_by_id')->load([
    'id' => $product_id
]);
$v_policy_price = 0;
if(check_array($result['vars']['pricePolicies'])){
    //Check chính sách giá để gán lại giá sản phẩm
    $v_arr_leverPrice = [];
    $policies = array_reverse($result['vars']['pricePolicies']);// 30/6-2023. chinh lay muc chiet khau nho nhat
    foreach ($policies as $policy) {
        if (intval($quality) >= intval($policy['quantity'])) {
            $v_policy_price = $policy['price'];
            break;
        }
    }
}
$result = $result['vars'];
$price = $result['discount_price'] ? $result['discount_price'] : $result['price'];
$price = $v_policy_price ? $v_policy_price : $price;

echo json_encode(['price'=>$price,'total_price'=>$price*$quality]);die;