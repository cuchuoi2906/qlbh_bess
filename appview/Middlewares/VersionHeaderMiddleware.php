<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 19/01/2021
 * Time: 14:54
 */

namespace AppView\Middlewares;


class VersionHeaderMiddleware
{

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        $version = $_SERVER['HTTP_VERSION'] ?? 1;
        $api = config('api');
        $api['version'] = (int)$version;
        config(['api' => $api]);
    }
}