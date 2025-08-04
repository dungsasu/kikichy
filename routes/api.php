<?php

use App\Http\Controllers\admin\Api\Api;
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


Route::post('/get-menu-items', [Api::class, 'get_menu_items'])->name('get-menu-items');
Route::post('/duplicate', [Api::class, 'duplicate'])->name('duplicate');
Route::post('/{status}/{value}', [Api::class, 'updateStatus'])->where([
    'status' => 'published|hot|new|gmc',
    'value' => '0|1'
])->name('updateStatus');

Route::post('/delete', [Api::class, 'delete'])->name('delete');
Route::post('/get-products-by-category', [Api::class, 'get_products_by_category'])->name('get-products-by-category');
Route::post('/get-news-by-category', [Api::class, 'get_news_by_category'])->name('get-news-by-category');

