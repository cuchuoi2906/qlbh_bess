<?php
/**
 * Created by PhpStorm.
 * User: ntdinh1987
 * Date: 4/25/2017
 * Time: 10:05 AM
 */

namespace AppView\Helpers;


class GoogleHelpers
{

//    private $config = [
//        'app_id'            => '202801604496-l9o40nbtckvecvmbt6c77thivrsotppc.apps.googleusercontent.com',
//        'app_secret'        => 'Xe52iz8LaS0P7DILe3yNVn4o',
//        'callback_url'      => '/auth/google/callback',
//    ];

    public function createClient()
    {
        $client = new \Google_Client();

        $callback = rtrim(url(), '/') . '/' . ltrim(config('google.callback_url'), '/');
        $client->setRedirectUri($callback);

        $client->addScope([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ]);
        //Set param google API
        $client->setClientId(config('google.app_id'));
//        $client->setClientSecret(config('google.app_secret'));
        $client->setAccessType('offline');

        return $client;
    }

    public function loginRedirectLink()
    {
        $client = $this->createClient();
        //Đây là URL bạn cần mở nếu chưa đăng nhập
        $auth_url = $client->createAuthUrl();

        return $auth_url;
    }

    function getInfo($accessCode)
    {
        $client = $this->createClient();
        $client->fetchAccessTokenWithAuthCode($accessCode);
        $access_token = $client->getAccessToken();

        $client->setAccessToken($access_token);

        if ($client->isAccessTokenExpired()) {
            //Truy cập bị hết hạn, cần xác thực lại
            //Chuyển hướng sang Google để lấy xác thực
            $auth_url = $client->createAuthUrl();
            header("Location: $auth_url");
            die();
        }

        $oauth2 = new \Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo_v2_me->get();
        $userInfo = [
            'email' => $userInfo->email,
            'name' => $userInfo->name,
            'image' => $userInfo->picture,
        ];

        return $userInfo;
    }

//    public function logoutLink()
//    {
//        $callback = url();
//
//        return sprintf($this->config['logout_url_pattern'], $callback);
//    }

}