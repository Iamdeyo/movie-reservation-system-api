<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'auth',], function () {
    Route::post('create-admin', [AuthController::class, 'storeAdmin'])->middleware(['auth:sanctum', 'role:admin']);

    Route::post('register', [AuthController::class, 'store']);
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum', 'role:admin,user']], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'profile']);
    });
});
Route::group(['prefix' => 'users',], function () {
    Route::get('', [UserController::class, 'index'])->middleware(['auth:sanctum', 'role:admin']);

    Route::group(['middleware' => ['auth:sanctum', 'role:admin,user']], function () {
        Route::get('{id}', [UserController::class, 'show']);
        Route::patch('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
    });
});
