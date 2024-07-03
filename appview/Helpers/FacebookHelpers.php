<?php
/**
 * Created by PhpStorm.
 * User: ntdinh1987
 * Date: 4/25/2017
 * Time: 10:05 AM
 */

namespace AppView\Helpers;


class FacebookHelpers
{

    private $config = [
        'app_id' => '354297375194819',
        'app_secret' => '2df1e93b5e11e6bc5577bf1fa7090e9c',
        'callback_url' => '/auth/facebook/callback',
        'login_url_pattern' => 'https://www.facebook.com/dialog/oauth?scope=public_profile,email&client_id=%s&redirect_uri=%s&state=%s',
    ];

    public function loginRedirectLink(string $redirectLink = '/')
    {
        $callback = rtrim(url(), '/') . '/' . ltrim($this->config['callback_url'], '/');

        return sprintf($this->config['login_url_pattern'], $this->config['app_id'], $callback, $redirectLink);
    }

    public function getAccessTokenFromAccessCode($accessCode)
    {
        $facebook_access_token_uri = "https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s";

        $callback = rtrim(url(), '/') . '/' . ltrim($this->config['callback_url'], '/');

        $facebook_access_token_uri = sprintf($facebook_access_token_uri, $this->config['app_id'], $callback, $this->config['app_secret'], $accessCode);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $facebook_access_token_uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        // Get access token
        $response = json_decode($response);
        $access_token = $response->access_token;

        return $access_token;
    }

    function getInfo($accessToken)
    {

        $graph_url = "https://graph.facebook.com/me?fields=id,name,gender,email,birthday&access_token=" . $accessToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

        $userInfo = json_decode($output, true);
        if ($userInfo['id'] ?? false) {
            $userInfo['image'] = 'https://graph.facebook.com/' . ($userInfo['id'] ?? 0) . '/picture';
        }


        return $userInfo;
    }

    public function logoutLink()
    {
        $callback = url();

        return sprintf($this->config['logout_url_pattern'], $callback);
    }

}