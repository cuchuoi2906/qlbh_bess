<?php

namespace AppView\Controllers;
use AppView\Repository\CategoryRepository;
use AppView\Repository\PostRepository;
use AppView\Repository\PostRepositoryInterface;
use AppView\Repository\ProvinceRepository;


class HomeController extends FrontEndController
{

    /**
     * @var PostRepositoryInterface
     */
    protected $post;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;
    /**
     * PostController constructor.
     * @param ProvinceRepository $post
     */

    protected $provinceRepository;

    public function __construct(PostRepositoryInterface $post,CategoryRepository $category_repository,ProvinceRepository $provinceRepository)
    {
        parent::__construct();
        $this->post = $post;
        $this->categoryRepository = $category_repository;
        $this->provinceRepository = $provinceRepository;
    }

    public function render()
    {
        $postAll = $this->post->allByCat(82,'NEWS');
        $category =$this->categoryRepository->getCategoryByID(60);
        $province = $this->provinceRepository->all();

        $use_job = config('users.use_job');
		$titlePage = "Vua Dược đồng hành cùng sự phát triển của Nhà Thuốc -  Vuaduoc.com";
		$descPage = " Vua dược chuyên bán lẻ thuốc, dược phẩm, thực phẩm chức năng, thiết bị y tế. Đồng thời cung cấp thông tin hữu ích về cách phòng ngừa, nhận biết các dấu hiệu mắc bệnh để đưa ra các giải pháp trị bệnh kịp thời.";
        
        return view('welcome')->render([
            'postAll' => $postAll,
            'category'=>$category,
            'province'=>$province,
            'titlePage'=>$titlePage,
            'descPage'=>$descPage,
            'use_job'=>$use_job,
        ]);
    }
	
	public function renderProduct($id = '')
    {
		$type = 'PRODUCTCOMPANY';
		$page = getValue('page', 'int', 'GET', 1, 0);
        $keyword = getValue('keyword', 'str', 'GET', '', 0);
        $is_hot = getValue('is_hot', 'int', 'GET', 1);
        $sort_type = getValue('sort_type', 'str', 'GET', 'ASC');
        if ($page < 1) {
            $page = 1;
        } elseif ($page > 999999999) {
            $page = 999999999;
        }
        $page_size = 28;
        //var_dump($type);
        //var_dump(empty($type));
        if(!empty($type)){
            $is_hot = -1;
            $page_size = 18;
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
        $params = [
            'page' => $page,
            'type'=>$type,
            'sort_by' => getValue('sort_by', 'str', 'GET', 'price'),
            'sort_type' => $sort_type,
            'category_id' => $id,
            'page_size' => $page_size,
            'keyword' => $keyword,
            'is_hot' => $is_hot,
            'arrIdsCate' => $arrIdsCate,
        ];
		
		$_SESSION["loggedFe"] = 1;
		$_SESSION["userIdFe"] = 2;
		$_SESSION["userNameFe"] = 'admin';
		$_SESSION["userLoginFe"] = 'admin';

        $data = model('products/index')->load($params);
        $productList = [];
        $pagination = [];
        if($data && isset($data['vars']['data'])){
            $productList  = $data['vars']['data'];
            $pagination  = collect_recursive($data['vars']['meta']['pagination']);
        }
        
        return view('homeProduct')->render([
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

}