<?php

use App\Http\Controllers\Web\AppealController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CatalogController;
use App\Http\Controllers\Web\FavouriteProductController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\OAuthController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('news', NewsController::class)->only([
    'index',
    'show'
]);

Route::get('/catalog/product/{slug}', [ProductController::class, 'index'])->name('product');
Route::get('/catalog/{slug?}', [CatalogController::class, 'index'])->name('catalog');


Route::get('/appeal', [AppealController::class, 'form'])->name('appeal.form');
Route::post('/appeal', [AppealController::class, 'send'])->name('appeal.send');

Route::prefix('/user')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('profile.orders');
    Route::get('/favourites', [FavouriteProductController::class, 'show'])->name('profile.favourite');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::prefix('/oauth')->group(function () {
    Route::get('/{provider}/redirect', [OAuthController::class, 'redirectToService'])->name('oauth.redirect');
    Route::get('/{provider}/login', [OAuthController::class, 'login'])->name('oauth.login');
});

Route::get('/cart', CartController::class)->middleware('auth.optional');

Route::get('/checkout', [OrderController::class, 'show'])->name('checkout.show')->middleware('auth');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.post')->middleware('auth');


Route::get('/{slug}', PageController::class);


