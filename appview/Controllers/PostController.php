<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen
 * Date: 4/27/2017
 * Time: 5:10 PM
 */

namespace AppView\Controllers;


use AppView\Repository\PostRepository;
use AppView\Repository\PostRepositoryInterface;
use VatGia\Cache\Facade\Cache;

class PostController extends FrontEndController
{

    /**
     * @var PostRepositoryInterface
     */
    protected $post;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post)
    {
        parent::__construct();
        $this->post = $post;
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
        return view('posts/detail')->render([
            'item' => $detail,
            'postCategory'=>$postCategory
        ]);
    }
    public function postListing($type,$id){
        $postAll = $this->post->allByType($type,16);
        if (!$postAll) {
            return redirect(url('index'));
        }
        $items = $postAll->data;
        $pagination = $postAll->meta->pagination;

        return view('posts/listing')->render(compact('items', 'pagination'));    
    }
}