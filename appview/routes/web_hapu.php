<?php

use AppView\Controllers\ChatController;
use VatGia\Helpers\Facade\Route;


Route::get(
    ['/', 'login'],
    [\AppView\Controllers\Auth\AuthController::class, 'showLoginHapu']
);
Route::post(['web/login', 'post.loginhapu'], [\AppView\Controllers\Auth\AuthController::class, 'postLoginHapu']);

Route::get('/order-list', [\AppView\Controllers\UserCartController::class, 'orderListHapu']);
Route::get(['/order-detail-{id}'], [\AppView\Controllers\UserCartController::class, 'orderDetailHapu']);
Route::get(['/order-product-detail-checked-{id}'], [\AppView\Controllers\UserCartController::class, 'orderDetailCheckedHapu']);
Route::post(['/order-product-price-hapu', 'post.orderproductpricehapu'], [\AppView\Controllers\Api\ProductController::class, 'postOrderProductHapu']);
Route::post('/order/update-stock-status', [\AppView\Controllers\Api\OrderController::class, 'updateStockStatus']);
Route::post('/order/update-check-product-order', [\AppView\Controllers\Api\OrderController::class, 'updateCheckProductOrder']);
Route::post('/order/update-status-pick-product-order', [\AppView\Controllers\Api\OrderController::class, 'updateStatusPickProductOrder']);
