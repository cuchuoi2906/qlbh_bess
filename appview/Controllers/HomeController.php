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
        $postAll = $this->post->allByCat(85,'NEWS');
        $category =$this->categoryRepository->getCategoryByID(60);
        $province = $this->provinceRepository->all();

        $use_job = config('users.use_job');
        
        return view('welcome')->render([
            'postAll' => $postAll,
            'category'=>$category,
            'province'=>$province,
            'use_job'=>$use_job
        ]);
    }

}