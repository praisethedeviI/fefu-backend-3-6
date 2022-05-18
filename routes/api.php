<?php

use App\Http\Controllers\Api\AppealController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ProductController;
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

Route::middleware('auth:sanctum')->get('/user',
    function (Request $request) {
        return $request->user();
    });

Route::apiResource('news', NewsController::class)->only([
    'index',
    'show',
]);

Route::apiResource('categories', CatalogController::class)->only([
    'index',
    'show',
]);


Route::apiResource('pages', PageController::class)->only([
    'index',
    'show'
]);

Route::prefix('catalog')->group(function () {
    Route::get('product/list', [ProductController::class, 'index']);
    Route::get('product/details', [ProductController::class, 'show']);
});

Route::post('appeal', [AppealController::class, 'send'])->name('appeal.api.send');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('register', [AuthController::class, 'register']);
