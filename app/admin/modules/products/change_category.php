<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/28/18
 * Time: 01:16
 */

require_once 'inc_security.php';

$product_id = getValue('product_id', 'int', 'POST', 0);
$category_id = getValue('category_id', 'int', 'POST', 0);

\App\Models\Product::update(['pro_category_id' => $category_id], 'pro_id = ' . $product_id);