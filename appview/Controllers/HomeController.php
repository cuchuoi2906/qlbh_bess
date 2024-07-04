<?php

namespace AppView\Controllers;
use AppView\Repository\CategoryRepository;
use AppView\Repository\PostRepository;
use AppView\Repository\PostRepositoryInterface;


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
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post,CategoryRepository $category_repository)
    {
        parent::__construct();
        $this->post = $post;
        $this->categoryRepository = $category_repository;
    }

    public function render()
    {
        $postAll = $this->post->allByCat(85,'NEWS');
        $category =$this->categoryRepository->getCategoryByID(60);
        
        return view('welcome')->render([
            'postAll' => $postAll,
            'category'=>$category
        ]);
    }

}