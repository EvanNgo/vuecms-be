<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout')->middleware('jwt');
    Route::put('refresh', 'AuthController@refresh')->middleware('jwt');
    Route::get('user-profile', 'AuthController@userProfile')->middleware('jwt');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'role'
], function ($router) {
    $roleController = 'RoleController@';
    Route::get('/', $roleController. 'index')->middleware(['jwt', 'permission.check:role_manager']);
    Route::put('add', $roleController. 'add')->middleware(['jwt', 'permission.check:role_manager']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function ($router) {
    $userController = 'UserController@';
    Route::get('/', $userController. 'index')->middleware(['jwt', 'permission.check:role_manager']);
    Route::put('add', $userController. 'add')->middleware(['jwt', 'permission.check:role_manager']);
    Route::put('update-role', $userController. 'updateRole')->middleware(['jwt', 'permission.check:role_manager']);
    Route::put('block', $userController. 'block')->middleware(['jwt', 'permission.check:role_manager']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'product'
], function ($router) {
    $productController = 'ProductController@';
    Route::get('/', $productController . 'index');
    Route::get('get', $productController . 'get');
    Route::post('add', $productController . 'add')->middleware(['jwt', 'permission.check:editor_product']);
    Route::put('delete', $productController . 'delete')->middleware(['jwt', 'permission.check:delete_product']);
    Route::put('update', $productController . 'update')->middleware(['jwt', 'permission.check:editor_product']);
    Route::put('force-delete', $productController . 'forceDelete')->middleware(['jwt', 'permission.check:force_delete_product']);
});