<?php

namespace AppView\Controllers;
use AppView\Repository\PostRepository;
use AppView\Repository\PostRepositoryInterface;


class HomeController extends FrontEndController
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

    public function render()
    {
        $postAll = $this->post->allByCat(85,'NEWS');
        return view('welcome')->render([
            'postAll' => $postAll
        ]);
    }

}