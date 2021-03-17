<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
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


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    // users CRUD
    Route::post('/user/create', [UserController::class, 'create']);
    Route::post('/user/update/{id}', [UserController::class, 'update']);
    Route::get('/user/get_all', [UserController::class, 'getAll']);
    Route::get('/user/get_by_id/{id}', [UserController::class, 'getById']);

    // file CRUD
    Route::post('/file/create', [FileController::class, 'create']);
    Route::get('/file/delete/{id}', [FileController::class, 'delete']);
    Route::get('/file/get_by_id/{id}', [FileController::class, 'getById']);
    Route::get('/file/get_all', [FileController::class, 'getAll']);

    // tag CRUD
    Route::post('/tag/create', [TagController::class, 'create']);
    Route::post('/tag/update/{id}', [TagController::class, 'update']);
    Route::get('/tag/delete/{id}', [TagController::class, 'delete']);
    Route::get('/tag/get_by_id/{id}', [TagController::class, 'getById']);
    Route::get('/tag/get_all', [TagController::class, 'getAll']);

    // post CRUD
    Route::post('/post/create', [PostController::class, 'create']);
    Route::post('/post/update/{id}', [PostController::class, 'update']);
    Route::get('/post/delete/{id}', [PostController::class, 'delete']);
    Route::get('/post/get_by_id/{id}', [PostController::class, 'getById']);
    Route::get('/post/get_all', [PostController::class, 'getAll']);
});

