<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/26/2019
 * Time: 1:47 PM
 */

namespace AppView\Controllers;

use VatGia\ControllerBase;
use AppView\Repository\UserRepository;

class UserCartController extends FrontEndController
{
    protected $userRepository;
    
    /**
     * ProductController constructor.
     * @param categoryRepository $post
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function index(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }

        $result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);

        $user = $this->userRepository->getById(intval($_SESSION['userIdFe']));

        return view('products/cart')->render([
            'result'=>$result,
            'user'=>$user
        ]);
    }
}