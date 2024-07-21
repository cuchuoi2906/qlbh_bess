<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen
 * Date: 4/27/2017
 * Time: 5:10 PM
 */

namespace AppView\Controllers;


use AppView\Repository\PostRepository;
use AppView\Repository\CategoryRepository;
use AppView\Repository\PostRepositoryInterface;
use VatGia\Cache\Facade\Cache;

class PostController extends FrontEndController
{

    /**
     * @var PostRepositoryInterface
     */
    protected $post;
    protected $category;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post,CategoryRepository $category)
    {
        parent::__construct();
        $this->post = $post;
        $this->category = $category;
    }

    /**
     * @param $slug
     * @param $id
     * @return mixed|string
     */
    public function detail($slug, $id)
    {
        $detail = $this->post->getByID($id);
        $categoryId = $detail->category->id;
        $postCategory = $this->post->allByCat($categoryId,'');
        $postCategory = $postCategory->data;
        $cateogryChildren = $this->category->getCategoryByIdAndType('NEWS',60);
        return view('posts/detail')->render([
            'item' => $detail,
            'id'=>$id,
            'categoryId'=>$categoryId,
            'postCategory'=>$postCategory,
            'cateogryChildren'=>$cateogryChildren
        ]);
    }
    public function postListing($type,$id){
        $idCate = !$id ? 82 : $id;
        $postAll = $this->post->allByType($type,16,$idCate);

        $cateogryChildren = $this->category->getCategoryByIdAndType('NEWS',60);

        return view('posts/listing')->render(compact('postAll','cateogryChildren','idCate'));    
    }
}