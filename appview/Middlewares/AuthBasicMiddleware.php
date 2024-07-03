<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 9/11/19
 * Time: 13:38
 */

namespace AppView\Middlewares;


class AuthBasicMiddleware
{

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        $valid_passwords = array("mario" => "carbonell");
        $valid_users = array_keys($valid_passwords);

        dd($_SERVER);
        $this->parseAuthHeaader();

        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $pass = $_SERVER['PHP_AUTH_PW'] ?? '';

        $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

        if (!$validated) {
            dd($_SERVER);

            return $this->unauth();
        }
    }

    public function unauth()
    {
        disable_debug_bar();
        header('Content-Type: application/json');
        header('WWW-Authenticate: Basic realm="My Realm"');
        http_response_code(401);
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode([
            'code' => 401,
            'error' => 'Unauthorized'
        ]);
        exit;
    }

    public function parseAuthHeaader()
    {
        $headers = \apache_request_headers();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? [];

        dd($token);
    }
}