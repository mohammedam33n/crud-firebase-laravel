<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Firebase authentication
Route::controller(AuthController::class)->group(function () {
    Route::get('users', 'index');
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('refresh', 'refresh');
    Route::get('/user-profile', 'userProfile');
    Route::post('/logout', 'logout');
    Route::put('/update', 'update');
    Route::get('/show-user', 'show');
    Route::post('delete-user', 'destroy');
    Route::post('destroy-multiple-user', 'destroyMultiple');
});


// Firebase Realtime Database
Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('store', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('update/{id}', [CategoryController::class, 'update']);
    Route::post('destroy/{id}', [CategoryController::class, 'destroy']);
});


// Cloud Fire store
Route::group(['prefix' => 'users', 'as' => 'user.'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('store', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('update/{id}', [UserController::class, 'update']);
    Route::post('destroy/{id}', [UserController::class, 'destroy']);
});
