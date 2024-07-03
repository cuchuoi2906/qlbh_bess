<?php
//Disable for fix session locking
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(3);

define('APP_START', microtime(true));

defined('ROOT') OR define('ROOT', realpath(dirname(__FILE__) . '/../'));

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/app/helpers.php';

$app = new \VatGia\Application();

$app->bind('notFoundHandler', function () {
    return function (Exception $e) {
        if (config('app.debug')) {
            throw $e;
        } else {
            //Ghi log lỗi
//            redirect('/');
            return json_encode([
                'code' => $e->getCode(),
                'error' => $e->getMessage(),
            ]);
        }
    };
});

$app->bind('methodNotAllowHandler', function () {
    return function (Exception $e) {
        if (config('app.debug')) {
            throw $e;
        } else {
            //Ghi log lỗi
//            redirect('/');
            return json_encode([
                'code' => $e->getCode(),
                'error' => $e->getMessage(),
            ]);
        }
    };
});

$app->bind('errorHandler', function () {
    return function (Exception $e) {
        if (config('app.debug')) {
            error_log($e->getMessage());
            throw $e;
        } else {
            //Ghi log lỗi
            error_log($e->getMessage());

            return json_encode([
                'code' => $e->getCode(),
                'error' => $e->getMessage() . '|' . $e->getLine(),
            ]);
        }
    };
});

$app->bind('phpErrorHandler', function () {
    return function (Throwable $e) {
        if (config('app.debug')) {
            error_log($e->getMessage());
            throw $e;
        } else {
            //Ghi log lỗi
            error_log($e->getMessage(), 1, config('app.admin_email'));

//            redirect('/');
            return json_encode([
                'code' => $e->getCode(),
                'error' => $e->getMessage(),
            ]);
        }
    };
});

$app->bind('shutdown', function () use ($app) {
    return function () use ($app) {

        //Đóng tất cả kết nối tại đây
        if (db_init::$links) {
            foreach (db_init::$links as &$link) {
                @mysqli_close($link);
            }
        }

        //Hiển thị debug bar
        if (
            config('app.debugbar')
            && config('app.debug')
            && !$app->runningInConsole()
            && !defined('IS_API_CALL')
        ) {
            echo view('debug/debug_bar')->render();
        }
    };
});

/**
 * Shutdown function
 */
register_shutdown_function(app('shutdown'));

$app->boot();

