<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::get('/post/{id}', [App\Http\Controllers\API\PostController::class, 'show'])->where('id', '[0-9]+');
Route::get('/post/user/{id}', [App\Http\Controllers\API\PostController::class, 'getUserPosts']);
Route::get('/post/filter', [\App\Http\Controllers\API\PostController::class, 'filter']);

Route::resource('/user', App\Http\Controllers\API\UserController::class)->only([
    'show',
]);

Route::get('/location/provinsi', [App\Http\Controllers\API\LocationController::class, 'provinsi']);
Route::get('/location/kabupaten', [App\Http\Controllers\API\LocationController::class, 'kabupaten']);
Route::get('/location/kecamatan', [App\Http\Controllers\API\LocationController::class, 'kecamatan']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
   
    Route::resource('/post', App\Http\Controllers\API\PostController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::resource('/user', App\Http\Controllers\API\UserController::class)->only([
        'index', 'update', 'destroy'
    ]);
});
