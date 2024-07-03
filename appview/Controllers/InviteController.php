<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 08/05/2021
 * Time: 09:25
 */

namespace AppView\Controllers;


use AppView\Repository\UserRepository;
use VatGia\Helpers\Facade\FlashMessage;

class InviteController extends FrontEndController
{

    public function invite($base64)
    {

        $parent_id = (int)base64_decode($base64);
        if (!$parent_id) {
            $parent_id = $_COOKIE['referer_id'];
        }

        if ($parent_id) {
            $parent = (new UserRepository())->getById($parent_id);
        }

        if (!($parent ?? false)) {
            FlashMessage::error('Người giới thiệu không tồn tại');
        } else {
            setcookie('referer_id', (int)$parent->id, time() + 30 * 86400);
        }

        return view('invite')->render(get_defined_vars());

    }

    public function inviteSuccess()
    {

        $link_adroid = 'https://play.google.com/store/apps/details?id=com.shopaf';
        $link_ios = 'https://apps.apple.com/vn/app/dododo24h/id1468280186';
        //Detect special conditions devices
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

        if ($iPod || $iPhone || $iPad) {
            $link = $link_ios;
        } else if ($Android) {
            $link = $link_adroid;
        } else {
            $link = $link_adroid;
        }

        return view('invite_success')->render(get_defined_vars());
    }
}