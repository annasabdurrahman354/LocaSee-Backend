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

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
   
    Route::resource('/post', App\Http\Controllers\API\PostController::class)->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);

    Route::get('/post/user/{id}', [App\Http\Controllers\API\PostController::class, 'getUserPosts']);
    Route::get('/filter/', [\App\Http\Controllers\API\PostController::class, 'filter']);

    Route::resource('/user', App\Http\Controllers\API\UserController::class)->only([
        'index', 'show', 'update', 'destroy'
    ]);

    Route::get('/type/', [App\Http\Controllers\API\TypeController::class, 'index']);

    Route::get('/address/provinsi/', [App\Http\Controllers\API\AddressController::class, 'provinsi']);
    Route::get('/address/kabupaten/', [App\Http\Controllers\API\AddressController::class, 'kabupaten']);
    Route::get('/address/kabupatenOnProvinsi/{id}', [App\Http\Controllers\API\AddressController::class, 'kabupatenByProvinsi']);
    Route::get('/address/kecamatan/', [App\Http\Controllers\API\AddressController::class, 'kecamatan']);
    Route::get('/address/kecamatanOnKabupaten/{id}', [App\Http\Controllers\API\AddressController::class, 'kecamatanByKabupaten']);
});
