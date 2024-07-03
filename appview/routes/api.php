<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/7/18
 * Time: 13:08
 */

use VatGia\Helpers\Facade\Route;


Route::group([
    'prefix' => 'auth',
    'before' => [
    ]
], function () {

    //Đăng nhập
    Route::post('/login', [\AppView\Controllers\Api\AuthController::class, 'login']);
    //Đăng ký
    Route::post('/register', [\AppView\Controllers\Api\AuthController::class, 'register']);

    //Check mã code được gửi đến đt khi đăng ký tài khoản
    Route::post('/check-confirm-code', [\AppView\Controllers\Api\AuthController::class, 'confirmCode']);

    //Gửi lại mã xác thực tài khoản
    Route::post('/resend-confirm-code', [\AppView\Controllers\Api\AuthController::class, 'resendConfirmCode']);

    Route::post('/forgot-password', [\AppView\Controllers\Api\AuthController::class, 'forgotPassword']);

    Route::post('/confirm-forgot-password-code', [\AppView\Controllers\Api\AuthController::class, 'confirmForgotPasswordCode']);

    Route::put('/update-ref-code', [\AppView\Controllers\Api\AuthController::class, 'updateRefCode']);

    Route::group([
        'before' => [
            \AppView\Middlewares\LoginRequire::class
        ]
    ], function () {
        //Lấy thông tin cá nhân
        Route::get('/me', [\AppView\Controllers\Api\AuthController::class, 'profile']);

        //Sửa thôn tin cá nhân
        Route::put('/me', [\AppView\Controllers\Api\AuthController::class, 'profile']);

        Route::post('/device', [\AppView\Controllers\Api\UserDeviceController::class, 'add']);

        Route::delete('/device/{device_id}', [\AppView\Controllers\Api\UserDeviceController::class, 'process']);

        Route::put('/me/change-phone', [\AppView\Controllers\Api\AuthController::class, 'changePhone']);
        Route::post('/me/confirm-change-phone', [\AppView\Controllers\Api\AuthController::class, 'confirmChangePhone']);

        Route::put('/me/referer-code', [\AppView\Controllers\Api\AuthController::class, 'changeRefCode']);

    });

});

Route::group([
    'before' => \AppView\Middlewares\LoginRequire::class
], function () {

    Route::get('/users/detail/{id}', [\AppView\Controllers\Api\UserController::class, 'detail']);
    Route::get('/users/detail/{id}/orders', [\AppView\Controllers\Api\OrderController::class, 'userOrders']);
    Route::delete('/users/remove/{id:i}', [\AppView\Controllers\Api\UserController::class, 'remove']);

    Route::get('/direct-users', [\AppView\Controllers\Api\CollaboratorsController::class, 'users']);
    // Danh sách cộng tác viên
    Route::get('/collaborators', [\AppView\Controllers\Api\CollaboratorsController::class, 'collaborators']);

// Danh sách notification status
    Route::get('notifications', [\AppView\Controllers\Api\NotificationsController::class, 'notifications']);

    Route::post('notifications/{id:i}/read', [\AppView\Controllers\Api\NotificationsController::class, 'read']);

    Route::get('/banks', [\AppView\Controllers\Api\UserBankController::class, 'index']);
    Route::post('/banks', [\AppView\Controllers\Api\UserBankController::class, 'add']);
    Route::put('/banks/{id:i}', [\AppView\Controllers\Api\UserBankController::class, 'edit']);
    Route::delete('/banks/{id:i}', [\AppView\Controllers\Api\UserBankController::class, 'remove']);

    Route::get('/address', [\AppView\Controllers\Api\UserAddressController::class, 'index']);
    Route::post('/address', [\AppView\Controllers\Api\UserAddressController::class, 'add']);
    Route::put('/address/{id:i}', [\AppView\Controllers\Api\UserAddressController::class, 'edit']);
    Route::delete('/address/{id:i}', [\AppView\Controllers\Api\UserAddressController::class, 'remove']);

    Route::group([
        'prefix' => 'cart'
    ], function () {

        Route::get('/', [\AppView\Controllers\Api\UserCartController::class, 'index']);

        Route::post('/', [\AppView\Controllers\Api\UserCartController::class, 'add']);

    });

    Route::group(['prefix' => 'wallet'], function () {

        Route::post('/transfer', [\AppView\Controllers\Api\UserWalletController::class, 'transfer']);

        Route::post('/charge', [\AppView\Controllers\Api\PaymentController::class, 'request']);

    });

    Route::get('/money-logs', [\AppView\Controllers\Api\UserMoneyLogController::class, 'index']);

    Route::post('/payment-request', [\AppView\Controllers\Api\UserPaymentRequestController::class, 'add']);

    Route::get('/report', [\AppView\Controllers\Api\ReportController::class, 'index']);

    Route::post('/money-request', [\AppView\Controllers\Api\MoneyBankingRequestController::class, 'request']);

    //Report money
    Route::group([
        'prefix' => 'reports'
    ], function () {

        Route::get('/money', [\AppView\Controllers\Api\ReportController::class, 'money']);
        Route::get('/order', [\AppView\Controllers\Api\ReportController::class, 'order']);
        Route::get('/point', [\AppView\Controllers\Api\ReportController::class, 'point']);

        Route::get('/all', [\AppView\Controllers\Api\ReportController::class, 'all']);

    });

    Route::group([
        'prefix' => 'products'
    ], function () {

        Route::post('/{id:i}/like', [\AppView\Controllers\Api\ProductController::class, 'like']);
        Route::post('/{id:i}/unlike', [\AppView\Controllers\Api\ProductController::class, 'unlike']);
        Route::get('/liked', [\AppView\Controllers\Api\ProductController::class, 'liked']);

    });

    Route::group([
        'prefix' => 'top-racing'
    ], function () {

        Route::get('/products', [\AppView\Controllers\Api\TopRacingCampaignController::class, 'products']);
//        Route::get('/products/{product_id}/list', []);
        Route::get('/{campaign_id}/top', [\AppView\Controllers\Api\TopRacingCampaignController::class, 'top']);
    });

});

Route::group([
    'prefix' => 'users'
], function () {

    //Thành viên mới
    Route::get('/', [\AppView\Controllers\Api\UserController::class, 'index']);
    Route::get('/best-seller', [\AppView\Controllers\Api\UserController::class, 'bestSeller']);
//    Route::get('/new', [\AppView\Controllers\Api\UserController::class, 'newUsers']);
});

// Danh sách tất cả danh mục
Route::get('/category', [\AppView\Controllers\Api\CategoryController::class, 'category']);

Route::group([
    'prefix' => 'products',
], function () {
    // Danh sách products
    Route::get('/', [\AppView\Controllers\Api\ProductController::class, 'products']);

    //Chi tiết sản phẩm
    Route::get('/{pro_id:i}', [\AppView\Controllers\Api\ProductController::class, 'productDetail']);

    //Hình ảnh sản phẩm theo product_id
    Route::get('/{pro_id:i}/images', [\AppView\Controllers\Api\ProductImagesController::class, 'images']);
});


// Dánh sách tất cả settings_website
Route::get('/settings', [\AppView\Controllers\Api\SettingsWebsiteController::class, 'settings']);
Route::get('/settings/bank-accounts', [\AppView\Controllers\Api\SettingsWebsiteController::class, 'bankAccounts']);

Route::get('/status/{setting_code}', [\AppView\Controllers\Api\SettingsWebsiteController::class, 'status']);

Route::get('/slider/{code}', [\AppView\Controllers\Api\SliderController::class, 'index']);


//========ORDER=====================
Route::group([
    'prefix' => 'order',
], function () {
    Route::group([
        'before' => [
            \AppView\Middlewares\LoginRequire::class
        ]
    ], function () {
        Route::get('/', [\AppView\Controllers\Api\OrderController::class, 'orders']);
        Route::get('/sub-orders', [\AppView\Controllers\Api\OrderController::class, 'subOrders']);
        //Tạo 1 order
        Route::post('/', [\AppView\Controllers\Api\OrderController::class, 'order']);
        Route::delete('/{pro_id:i}', [\AppView\Controllers\Api\OrderController::class, 'order']);
        Route::put('/{pro_id:i}', [\AppView\Controllers\Api\OrderController::class, 'order']);
    });
});


//=============Logs======================
Route::group([
    'prefix' => 'log',
    'before' => [
        \AppView\Middlewares\LoginRequire::class
    ]
], function () {
    Route::get('/commission', [\AppView\Controllers\Api\LogsController::class, 'commissions']);
    Route::get('/money', [\AppView\Controllers\Api\LogsController::class, 'moneyAdds']);

});

Route::group([
    'prefix' => 'posts',
], function () {
    Route::get('/', [\AppView\Controllers\Api\PostsController::class, 'posts']);

    Route::get('/{id:i}', [\AppView\Controllers\Api\PostDetailController::class, 'postDetail']);
});

Route::group([
    'prefix' => 'provinces',
], function () {
    Route::get('/', [\AppView\Controllers\Api\ProvinceController::class, 'provinces']);
    Route::get('/districts', [\AppView\Controllers\Api\ProvinceController::class, 'districts']);
    Route::get('/districts/wards', [\AppView\Controllers\Api\ProvinceController::class, 'wards']);
});

//============Add Money=============================
Route::group([
    'prefix' => 'add-money',
    'before' => [
        \AppView\Middlewares\LoginRequire::class
    ]
], function () {
    Route::post('/request', [\AppView\Controllers\Api\PaymentController::class, 'request']);
//    Route::get('/money', [\AppView\Controllers\Api\LogsController::class, 'moneyAdds']);

});


Route::post(['/payment-callback/bpn', 'payment-callback-bpn'], [\AppView\Controllers\BaoKimBPNController::class, 'bpn']);

Route::post('/upload', [\AppView\Controllers\Api\UploadController::class, 'upload']);

Route::get('/payment/banks', [\AppView\Controllers\Api\PaymentController::class, 'banks']);

Route::post('/testbpn', [\AppView\Controllers\BaoKimBPNController::class, 'testBpn']);

Route::post('/test-json', [\AppView\Controllers\Api\TestController::class, 'test']);
Route::get('/test-json', [\AppView\Controllers\Api\TestController::class, 'test']);