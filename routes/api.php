<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\TheatersController;
use App\Http\Controllers\UserController;
use App\Models\Genres;
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

Route::group(['prefix' => 'movies'], function () {
    Route::get('all', [MoviesController::class, 'all']);
    Route::get('', [MoviesController::class, 'index']);
    Route::post('', [MoviesController::class, 'store']);
    Route::get('{id}', [MoviesController::class, 'show']);
    Route::patch('{id}', [MoviesController::class, 'update']);
    Route::delete('{id}', [MoviesController::class, 'destroy']);
});

Route::group(['prefix' => 'theaters'], function () {
    Route::get('', [TheatersController::class, 'index']);
    Route::get('{id}', [TheatersController::class, 'show']);
    Route::post('', [TheatersController::class, 'store']);
    Route::patch('{id}', [TheatersController::class, 'update']);
    Route::delete('{id}', [TheatersController::class, 'destroy']);
});

Route::group(['prefix' => 'genres'], function () {
    Route::get('', [GenresController::class, 'index']);
    Route::post('', [GenresController::class, 'store']);
    Route::patch('{id}', [GenresController::class, 'update']);
    Route::delete('{id}', [GenresController::class, 'destroy']);
});
