<?php

use App\Http\Controllers\app\AuthApp;
use App\Http\Controllers\app\Product;
use App\Http\Controllers\app\Banner;
use App\Http\Controllers\app\Commons;
use App\Http\Controllers\app\Cart;

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

//admin
Route::post('login', [AuthApp::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthApp::class, 'logout'])->name('logout');
    //==============================member=====================================
    Route::get('member_info', [AuthApp::class, 'member_info'])->name('member_info');
});
//==============================products====================================
Route::post('products', [Product::class, 'get_products'])->name('products');
Route::post('categories', [Product::class, 'get_categories'])->name('categories');
Route::get('detail_product', [Product::class, 'detail_product'])->name('detail_product');
//==============================banners=====================================
Route::get('banners', [Banner::class, 'banners'])->name('banners');
Route::get('menu-groups', [Commons::class, 'menu_groups'])->name('menu-groups');
Route::get('about-us', [Commons::class, 'about_us'])->name('about-us');

Route::post('cart', [Cart::class, 'total_cart'])->name('cart');
