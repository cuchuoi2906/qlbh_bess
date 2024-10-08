<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/2/16
 * Time: 11:20 PM
 */

return [
    // Biến môi trường
    'env' => env('APP_ENV', 'development'),

    // Chế độ dev: local hoặc api
    'mode' => (env('APP_ENV', 'production') !== 'production') ? env('APP_MODE') : 'local',

    // Đường dẫn lấy dữ liệu qua api
    'api_url_api' => env('APP_API_URL'),

    // Bật chế độ debug. Riêng app envi là production thì debug luôn = false
    'debug' => (bool)env('APP_DEBUG') && (env('APP_ENV', 'production') !== 'production'),

    //Email nhận thông báo khi có lỗi nghiêm trọng
    'admin_email' => 'ntdinh1987@gmail.com',

    //Thư mục app
    'app_dir' => 'app',

    'app_uri' => env('APP_URL'),

    'url' => env('APP_URL'),

    'debugbar' => env('DEBUGBAR', true),

    //Ngôn ngữ
    'locale' => 'vn',

    'locale_fallback' => 'en',

    'locale_path' => '/resources/lang',

    //Tự động lưu lại ngôn ngữ
    'locale_auto_save' => true,

    'locales' => [
        'vn' => [
            'code' => 'vn',
            'name' => 'Tiếng Việt',
            'icon' => '/assets/v2/images/icons/flags/vietnam.png'
        ],
//        'en' => [
//            'code' => 'en',
//            'name' => 'English',
//            'icon' => '/assets/v2/images/icons/flags/us.png'
//        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        \VatGia\Cache\CacheServiceProvider::class,
        \VatGia\Queue\QueueServiceProvider::class,
        \VatGia\Helpers\Translation\TranslationServiceProvider::class,

        \VatGia\Api\ApiServiceProvider::class,

//        \VatGia\Admin\AdminServiceProvider::class,

        \AppView\AppViewServiceProvider::class,
    ],

    'jwt_key' => 'lksjdaslkdjaskldjaskjdaksdjlkj',

    'speed_sms_access_token' => 'PLS35XMo1v6oK_9ruLWy1mPaf3ZhzsxD',

    'send_grid_api_key' => env('SEND_GRID_API_KEY', 'key send grid'),
];