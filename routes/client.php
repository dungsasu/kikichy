<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\client\Product\Product as ProductClient;
use App\Http\Controllers\client\Product\ProductCategories as ProductCategoriesClient;
use App\Http\Controllers\client\Tour\Tour as TourClient;
use App\Http\Controllers\client\Tour\TourCategories as TourCategoriesClient;
use App\Http\Controllers\client\Home\Home as HomeClient;
use App\Http\Controllers\client\Member\Member as MemberClient;


Route::get('/san-pham/{alias}', [ProductClient::class, 'detail'])
    ->where('category', '^(?!admin_nama).*$')
    ->defaults('category', function () {
        return 'san-pham';
    })
    ->name('client.product');

Route::get('/tour/{category}/{alias}', [TourClient::class, 'detail'])
    ->where('category', '^(?!admin_nama).*$')
    ->name('client.tour');

Route::get('/danh-muc/{alias}', [ProductCategoriesClient::class, 'home'])
    ->name('client.product-categories')
    ->where('alias', '^(?!admin_nama).*$');

Route::get('/danh-muc-tour/{alias}', [TourCategoriesClient::class, 'home'])
    ->name('client.tour-categories')
    ->where('alias', '^(?!admin_nama).*$');
Route::get('/', [HomeClient::class, 'index'])->name('client.home.index');
Route::get('/api/get-gallery-component/{id}', [ProductClient::class, 'getGalleryComponent'])->name('client.gallery-component');
Route::get('/dang-ky-doanh-nghiep', [MemberClient::class, 'showRegisterBusiness'])->name('client.register_business');
Route::post('/dang-ky-doanh-nghiep', [MemberClient::class, 'registerBusiness'])->name('client.register_business.store');
Route::post('/check-duplicate', [MemberClient::class, 'checkDuplicate'])->name('client.check_duplicate');

Route::get('/dang-nhap-doanh-nghiep', [MemberClient::class, 'showLoginBusiness'])->name('client.login_business');
Route::post('/dang-nhap-doanh-nghiep', [MemberClient::class, 'loginBusiness'])->name('client.login_business.store');
Route::post('/dang-xuat-doanh-nghiep', [MemberClient::class, 'logout'])->name('client.logout_business');
Route::get('/doanh-nghiep/logout', [MemberClient::class, 'logout'])->name('client.logout_business_get');
Route::post('/update-activity', [MemberClient::class, 'updateActivity'])->name('client.update_activity');

// Business Profile Routes (cần đăng nhập)
Route::middleware(['auth:members', 'auto.logout'])->group(function () {
    Route::get('/ho-so-doanh-nghiep', [MemberClient::class, 'showProfile'])->name('client.business.profile');
    Route::post('/ho-so-doanh-nghiep/cap-nhat', [MemberClient::class, 'updateInfo'])->name('client.business.update_info');
    Route::post('/ho-so-doanh-nghiep/cap-nhat-avatar', [MemberClient::class, 'updateAvatar'])->name('client.business.update_avatar');
    Route::get('/ho-so-doanh-nghiep/thong-tin-lien-he', [MemberClient::class, 'showInfo'])->name('client.business.info');
    Route::post('/ho-so-doanh-nghiep/thong-tin-lien-he/cap-nhat', [MemberClient::class, 'updateContactInfo'])->name('client.business.update_contact_info');
    Route::get('/ho-so-doanh-nghiep/trang-dieu-hanh', [MemberClient::class, 'showOrders'])->name('client.business.orders');
    Route::post('/ho-so-doanh-nghiep/trang-dieu-hanh/luu', [MemberClient::class, 'saveOrders'])->name('client.business.orders.save');
    Route::get('/ho-so-doanh-nghiep/quan-ly-tour', [MemberClient::class, 'showTourManagement'])->name('client.business.tour_management');
    Route::post('/ho-so-doanh-nghiep/quan-ly-tour/luu', [MemberClient::class, 'saveTour'])->name('client.business.tour_management.save');
    Route::get('/ho-so-doanh-nghiep/phan-loai-khach', [MemberClient::class, 'showCategories'])->name('client.business.categories');
    Route::get('/ho-so-doanh-nghiep/thong-bao-email', [MemberClient::class, 'showNotifications'])->name('client.business.notifications');
    Route::get('/ho-so-doanh-nghiep/dat-cho-tai-chinh', [MemberClient::class, 'showSettings'])->name('client.business.settings');
});

// API routes không yêu cầu authentication
Route::get('/get-cities-by-country', [MemberClient::class, 'getCitiesByCountry'])->name('client.get_cities_by_country');
