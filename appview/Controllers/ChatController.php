<?php

/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 27/09/2021
 * Time: 22:38
 */

namespace AppView\Controllers;


use AppView\Repository\UserRepository;
use VatGia\Helpers\Facade\FlashMessage;

class ChatController extends FrontEndController {

    public function index() {

        disable_debug_bar();
        return view('chat')->render();
    }
}
