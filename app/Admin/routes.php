<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->resource('users', UserController::class);
    $router->resource('foods', FoodsController::class);
    $router->resource('food-types', FoodTypeController::class);
    $router->resource('orders', OrderController::class);
    $router->get('/', 'HomeController@index')->name('home');

    // Route::post('send',[PushNotificationController::class, 'bulksend'])->name('bulksend');
    // Route::get('all-notifications', [PushNotificationController::class, 'index']);
    // Route::get('get-notification-form', [PushNotificationController::class, 'create']);


});
