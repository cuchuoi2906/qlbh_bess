<?php

/**
 * Xem hướng dẫn sử dụng app('route') tại link:
 * https://github.com/mrjgreen/phroute
 */

use AppView\Controllers\ChatController;
use VatGia\Helpers\Facade\Route;

Route::get(
    ['/', 'index'],
    [\AppView\Controllers\HomeController::class, 'renderProduct']
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
Route::get('/products', [\AppView\Controllers\ProductController::class, 'getProducts']);
//Route::get('/products/{type}', [\AppView\Controllers\ProductController::class, 'getProducts']);
Route::get('/products/{type}-{id}', [\AppView\Controllers\ProductController::class, 'getProducts']);
Route::post('/increment_product', [\AppView\Controllers\Api\ProductController::class, 'incrementProduct']);
Route::get('/loyal-client', [\AppView\Controllers\Auth\AuthController::class, 'loyalClient']);
//Route::get('/cart', [\AppView\Controllers\ProductController::class, 'getProductsCart']);


Route::group([
    'prefix' => 'cart'
], function () {

    Route::get('/', [\AppView\Controllers\UserCartController::class, 'index']);

    Route::post('/', [\AppView\Controllers\Api\UserCartController::class, 'add']);

});
Route::post('/order', [\AppView\Controllers\Api\OrderController::class, 'order']);
Route::get('/gioi-thieu-vua-duoc', [\AppView\Controllers\Auth\AuthController::class, 'introduce']);
Route::get('/dong-hanh-vua-duoc', [\AppView\Controllers\Auth\AuthController::class, 'companion']);
Route::get('/huong-dan-dat-hang', [\AppView\Controllers\Auth\AuthController::class, 'guideOrder']);
Route::get('/dieu-khoan-su-dung', [\AppView\Controllers\Auth\AuthController::class, 'dieuKhoanSuDung']);
Route::get('/chinh-sach-bao-mat', [\AppView\Controllers\Auth\AuthController::class, 'chinhSachBaoMat']);
Route::get('/chinh-sach-van-chuyen', [\AppView\Controllers\Auth\AuthController::class, 'chinhSachVanChuyen']);
Route::get('/chinh-sach-giai-quyet-khieu-nai', [\AppView\Controllers\Auth\AuthController::class, 'chinhSachkhieuNai']);
Route::get('/chinh-sach-kiem-hang-va-doi-tra', [\AppView\Controllers\Auth\AuthController::class, 'kiemHangVaDoiTra']);
Route::get('/payment/{id}', [\AppView\Controllers\PaymentController::class, 'getBanksByOrderId']);
Route::get('/san-pham/{rewrite}-{pro_id}', [\AppView\Controllers\ProductController::class, 'getProductDetail']);
