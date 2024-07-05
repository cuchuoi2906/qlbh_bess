<?php

/**
 * Xem hướng dẫn sử dụng app('route') tại link:
 * https://github.com/mrjgreen/phroute
 */

use AppView\Controllers\ChatController;
use VatGia\Helpers\Facade\Route;

Route::get(
    ['/', 'index'],
    [\AppView\Controllers\HomeController::class, 'render']
);
Route::get(
    ['/dang-ky', 'index'],
    [\AppView\Controllers\RegisterController::class, 'render']
);
Route::post(['/dang-ky-thanh-vien', 'post.dangkythanhvien'], [\AppView\Controllers\Auth\AuthController::class, 'dangkythanhvien']);

Route::get(['/posts/{slug}-{id}', 'post.detail'], [\AppView\Controllers\PostController::class, 'detail']);

Route::get(
    ['/login', 'login'],
    [\AppView\Controllers\Auth\AuthController::class, 'showLoginForm']
);

Route::get(
    ['/idvg/login-callback', 'login-callback'],
    [\AppView\Controllers\Auth\AuthController::class, 'loginCallback']
);

Route::group([
    'before' => [
        \AppView\Middlewares\LoginRequire::class
    ]
], function () {

    Route::get(
        ['/logout', 'logout'],
        [\AppView\Controllers\Auth\AuthController::class, 'logout']
    );

    Route::get(
        ['/profile', 'profile'],
        [\AppView\Controllers\Auth\AuthController::class, 'showProfile'],
        [
            'before' => ['auth'],
        ]
    );
});

Route::get('/policy', function () {

    return view('policy')->render();
});

Route::get(
    ['/payment-callback', 'payment-callback'],
    function () {
        return 'Success';
    }
);

Route::get(['/invite/success', 'invite.success'], [\AppView\Controllers\InviteController::class, 'inviteSuccess']);
Route::get(['/invite/{base64}', 'invite'], [\AppView\Controllers\InviteController::class, 'invite']);
Route::get(['register/{referer_id}', 'register'], [\AppView\Controllers\Auth\AuthController::class, 'register']);
Route::post(['register', 'post.register'], [\AppView\Controllers\Auth\AuthController::class, 'postRegister']);

Route::get('/chat', [ChatController::class, 'index']);
Route::get(['/bai-viet/{type}-{id}', 'post.listing'], [\AppView\Controllers\PostController::class, 'postListing']);
Route::post(['web/login', 'post.login'], [\AppView\Controllers\Auth\AuthController::class, 'postLogin']);
Route::get('/products', [\AppView\Controllers\ProductController::class, 'products']);