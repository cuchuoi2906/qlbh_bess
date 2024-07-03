<?php
/**
 * Created by vatgia-framework.
 * Date: 6/26/2017
 * Time: 4:21 PM
 */

return [
    'prefix' => 'api',
    'filter' => [
        'before' => [
            \AppView\Middlewares\VersionHeaderMiddleware::class,
            \AppView\Middlewares\BeforeLogRequestMiddleware::class,
//            \AppView\Middlewares\AuthBasicMiddleware::class
        ],
        'after' => [
            \AppView\Middlewares\AfterLogRequestMiddleware::class
        ]
    ],
    'app_repository_enable' => env('API_APP_REPOSITORY_ENABLE', false),

    'routes' => 'appview/routes/api.php'
];