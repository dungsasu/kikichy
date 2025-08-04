<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\client\Cart\CartClient as CartClient;
use App\Services\Fast\FastService;
use App\Services\ProductService;
use App\Services\OrderService;

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

// services
Route::prefix('/service')->group(function () {
    Route::prefix('/product')->group(function () {
        Route::get('/update-price/{id}', [ProductService::class, 'updatePrice'])->name('service.product.update.price');
        Route::get('/update-quantity/{id}', [ProductService::class, 'updateQuantity'])->name('service.product.update.quantity');
        Route::get('/update-quantity', [ProductService::class, 'updateAllQuantity'])->name('service.product.update.quantity.all');
        Route::get('/gmc', [ProductService::class, 'sendToGoogleMerchant'])->name('service.product.gmc');
    });

    Route::prefix('/order')->group(function () {
        Route::get('/update', [OrderService::class, 'getUpdateOrder'])->name('service.order.update');
    });
});

//fast api

Route::get('/all-items-fast', function () {
    $service = new FastService();
    return $service->allItemsFast();
});

Route::get('/getItemInventory', function (Illuminate\Http\Request $request) {
    $param = $request->query('param');
    $service = new FastService();
    return $service->getItemInventory($param);
});

Route::get('/update-all-inventory', function () {
    $service = new FastService();
    return $service->updateAllInventory();
});

Route::post('/api/inventoryitems/store-update', function () {
    $service = new FastService();
    return $service->apiStoreUpdate();
});

Route::post('/create_voucher.api', function () {
    $service = new FastService();
    return $service->createVoucher();
})->name('create_voucher.api');

Route::post('/update_customer.api', function () {
    $service = new FastService();
    return $service->updateCustomer();
})->name('update_customer.api');


Route::post('/apply-voucher', [CartClient::class, 'applyVoucher'])->name('apply-voucher');
Route::post('/delete-voucher', [CartClient::class, 'deleteVoucher'])->name('delete-voucher');



require __DIR__.'/admin.php';
require __DIR__.'/client.php';