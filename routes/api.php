<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::group(['middleware' => ['role:admin', 'auth:sanctum']], function () {
    //Route::post('/logout', AuthenticationController::class, 'logout');
    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('admin/logout', 'logout');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('admin/user', 'index');
    });
});

Route::group(['middleware' => ['role:user', 'auth:sanctum']], function () {
    //Route::post('/logout', AuthenticationController::class, 'logout');
    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('user/logout', 'logout');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('user/user', 'index');
    });
});


