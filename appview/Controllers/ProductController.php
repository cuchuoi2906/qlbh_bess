<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 1:47 PM
 */

namespace AppView\Controllers;

use AppView\Repository\CategoryRepository;
use VatGia\ControllerBase;


class ProductController extends FrontEndController
{
    protected $categoryRepository;

    /**
     * ProductController constructor.
     * @param categoryRepository $post
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }

    public function getProducts($type = '',$id = '')
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $page = getValue('page', 'int', 'GET', 1, 0);
        $keyword = getValue('keyword', 'str', 'GET', '', 0);
        $is_hot = getValue('is_hot', 'int', 'GET', 1);
        $sort_type = getValue('sort_type', 'str', 'GET', 'ASC');
        if ($page < 1) {
            $page = 1;
        } elseif ($page > 999999999) {
            $page = 999999999;
        }
        $page_size = 46;
        //var_dump($type);
        //var_dump(empty($type));
        if(!empty($type)){
            $is_hot = -1;
            $page_size = 36;
        }

		$categoryByType = [];
        if($type != ''){
            $categoryByType = $this->categoryRepository->getCategoryByType($type);
        }
		$categoryById = [];
		$categoryByParentId = [];
		$arrIdsCate = [];
		if(intval($id) >0){
			$categoryById = $this->categoryRepository->getCategoryByID($id);
			if($categoryById->parent_id > 0){
				$categoryByParentId = $this->categoryRepository->getCategoryByID($categoryById->parent_id);
			}
			$type = $categoryById->type;
			if(!$categoryById->parent_id){
				$subcategoryIds = $categoryById->childs->toArray();
				$arrIdsCate = array_column($subcategoryIds, 'id');
				$arrIdsCate[] = $categoryById->id;
			}
		}
		$sort_by = getValue('sort_by', 'str', 'GET', 'price');
		
		if($is_hot && !isset($_GET['sort_by'])){
			$sort_by = 'updated_at';
			$sort_type = 'DESC';
		}
        $params = [
            'page' => $page,
            'type'=>$type,
            'sort_by' => $sort_by,
            'sort_type' => $sort_type,
            'category_id' => $id,
            'page_size' => $page_size,
            'keyword' => $keyword,
            'is_hot' => $is_hot,
            'arrIdsCate' => $arrIdsCate,
        ];

        $data = model('products/index')->load($params);
        $productList = [];
        $pagination = [];
        if($data && isset($data['vars']['data'])){
            $productList  = $data['vars']['data'];
            $pagination  = collect_recursive($data['vars']['meta']['pagination']);
        }
		
        return view('products/listing')->render([
            'productList' => $productList,
            'pagination'=>$pagination,
            'categoryByType'=>$categoryByType,
            'keyword'=>$keyword,
            'type'=>$type,
            'sort_type'=>$sort_type,
            'is_hot'=>$is_hot,
			'categoryById' => $categoryById,
            'categoryByParentId' => $categoryByParentId
        ]);
    }

    public function getProductDetail($pro_id)
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $pro_id = (int)$pro_id;

        $data = model('products/get_by_id')->load([
            'id' => $pro_id
        ]);

        if (!$data['vars']) {
            return redirect(url('products'));
        }
		$categoryById = [];
		$categoryByParentId = [];
		$type = '';
		if(check_array($data['vars']['category'])){
			$id = $data['vars']['category']['id'];
			if(intval($id) >0){
				$categoryById = $this->categoryRepository->getCategoryByID($id);
				if($categoryById->parent_id > 0){
					$categoryByParentId = $this->categoryRepository->getCategoryByID($categoryById->parent_id);
				}
				$type = $categoryById->type;
			}
		}
		
        return view('products/detail')->render([
            'product'=>$data['vars'],
			'categoryById'=>$categoryById,
			'categoryByParentId'=>$categoryByParentId,
			'type'=>$type,
        ]);
    }

    public function postLike($product_id)
    {

        $result = repository('products/like')->post([
            'product_id' => (int)$product_id,
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }

    public function postUnlike($product_id)
    {

        $result = repository('products/unlike')->post([
            'product_id' => (int)$product_id,
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }

    public function getLiked()
    {

        $result = repository('products/liked')->post([
            'user_id' => app('auth')->u_id
        ]);

        return $result['vars'];
    }
    public function getProductsCart($type = '',$id = '')
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $page = getValue('page', 'int', 'GET', 1, 0);

        return view('products/cart')->render();
    }
	public function getProductDetail2($pro_id)
    {
        $pro_id = (int)$pro_id;

        $data = model('products/get_by_id')->load([
            'id' => $pro_id
        ]);

        if (!$data['vars']) {
            return redirect(url('products'));
        }
		$categoryById = [];
		$categoryByParentId = [];
		$type = '';
		if(check_array($data['vars']['category'])){
			$id = $data['vars']['category']['id'];
			if(intval($id) >0){
				$categoryById = $this->categoryRepository->getCategoryByID($id);
				if($categoryById->parent_id > 0){
					$categoryByParentId = $this->categoryRepository->getCategoryByID($categoryById->parent_id);
				}
				$type = $categoryById->type;
			}
		}
		
        return view('products/detail2')->render([
            'product'=>$data['vars'],
			'categoryById'=>$categoryById,
			'categoryByParentId'=>$categoryByParentId,
			'type'=>$type,
        ]);
    }
}