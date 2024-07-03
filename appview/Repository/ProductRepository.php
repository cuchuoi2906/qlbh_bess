<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/9/18
 * Time: 10:32
 */

namespace AppView\Repository;


class ProductRepository
{

    public function newProducts($limit = 6, $category_id = null, $product_id = null)
    {
        $result = model('products/new')->load([
            'limit' => (int)$limit,
            'category_id' => $category_id,
            'product_id' => $product_id,
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function hotProducts($limit = 6, $category_id = null)
    {
        $result = model('products/new')->load([
            'limit' => (int)$limit,
            'category_id' => $category_id,
            'is_hot' => true
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }


    /**
     * @param $id
     * @return bool|\VatGia\Helpers\Collection
     */
    public function detail($id)
    {
        $result = model('products/get_by_id')->load([
            'id' => (int)$id
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function getList($category_id)
    {
        $result = model('products/index')->load([
            'category_id' => (int)$category_id,
            'page' => getValue('page'),
            'page_size' => 6
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function productDriver()
    {
        $result = model('products/get_has_driver')->load([]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }
}