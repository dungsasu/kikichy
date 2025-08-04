<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\Authentications\Login;
use App\Http\Controllers\admin\Member\Member;
use App\Http\Controllers\admin\User\User;
use App\Http\Controllers\admin\Config\Config;
use App\Http\Controllers\admin\Menu\MenuGroups;
use App\Http\Controllers\admin\Menu\MenuItems;
use App\Http\Controllers\admin\Role\Role;
use App\Http\Controllers\admin\Tour\Tour;
use App\Http\Controllers\admin\Tour\TourCategories;
use App\Http\Controllers\admin\Tour\TourTypes;
use App\Http\Controllers\admin\Product\Fast;
use App\Http\Controllers\admin\Product\Size;
use App\Http\Controllers\admin\Product\Color;
use App\Http\Controllers\admin\Order\Order;
use App\Http\Controllers\admin\Collection\Collection;
use App\Http\Controllers\admin\Fashion\Fashion;
use App\Http\Controllers\admin\News\News;
use App\Http\Controllers\admin\News\NewsCategories;
use App\Http\Controllers\admin\Destination\Destination;
use App\Http\Controllers\admin\Destination\DestinationCategories;
use App\Http\Controllers\admin\Contact\Contact;
use App\Http\Controllers\admin\Customer\Customer;
use App\Http\Controllers\admin\Store\Store;
use App\Http\Controllers\admin\VnUnits\VnUnits;
use App\Http\Controllers\admin\Content\Content;
use App\Http\Controllers\admin\Content\ContentCategories;
use App\Http\Controllers\admin\Translation\Translation;
use App\Http\Controllers\admin\Extend\Extend;
use App\Http\Controllers\admin\Campaign\Campaign;
use App\Http\Controllers\admin\Locale\Locale;
// use App\Http\Controllers\admin\Voucher\Voucher;
use App\Http\Controllers\admin\Banner\Banner;
use App\Http\Controllers\admin\Banner\BannerCategory;
use App\Http\Controllers\admin\Filter\Filter;
use App\Http\Controllers\admin\Filter\FilterGroup;
use App\Http\Controllers\admin\Filter\FilterCategory;
use App\Http\Controllers\admin\Filter\FilterTable;
use App\Http\Controllers\admin\Promotion\DiscountCategory;
use App\Http\Controllers\admin\Promotion\Voucher;
use App\Http\Controllers\admin\Recruitment\Recruitment;
use App\Http\Controllers\admin\Comment\ProductComment;
use App\Http\Controllers\admin\Dashboard\Dashboard;
use App\Http\Controllers\admin\Country\Country;
use App\Http\Controllers\admin\City\City;
use App\Http\Controllers\admin\Tour\Instructions;
use App\Http\Controllers\admin\Tour\Experiential_style;

//------------------------admin------------------------
Route::post('/api/save-translation', [Translation::class, 'saveKeyword'])->name('admin.save-translation');
Route::post('/api/delete-translation', [Translation::class, 'deleteKeyword'])->name('admin.delete-translation');
Route::get('/api/members', [Voucher::class, 'getMembers'])->name('admin.getMembers');

Route::get('/login', [Login::class, 'index'])->name('login');
Route::get('/logout', [Login::class, 'logout'])->name('logout');

Route::post('/authenticate', [Login::class, 'authenticate'])->name('authenticate');
Route::prefix('/register')->group(function () {
    Route::get('/', [Login::class, 'register'])->name('register');
    Route::post('/', [Login::class, 'add_user'])->name('add_user');
});

Route::get('/locale/{locale}', [Locale::class, 'changeLocale'])->name('locale.change');

Route::get('/permission', [Controller::class, 'permission'])->name('permission');

Route::prefix(config('variables.admin'))->group(function () {
    Route::middleware(['auth', 'permission', 'locale'])->group(function () {
        Route::post('/filter', [BaseController::class, 'submit_filter'])->name('filter');
        Route::get('/menu-list-category', [MenuItems::class, 'list_category'])->name('menu.list_category');
        Route::get('/menu-list-item', [MenuItems::class, 'list_item'])->name('menu.list_item');
        Route::get('/create-link', [MenuItems::class, 'create_link'])->name('menu.create_link');

        Route::get('/', [Dashboard::class, 'index'])->name('dashboard');
        Route::get('/users', [User::class, 'index'])->name('admin.user.index');
        Route::get('/users/edit/{id}', [User::class, 'edit'])->name('admin.user.edit');
        Route::get('/users/create', [User::class, 'create'])->name('admin.user.create');
        Route::post('/users/save', [User::class, 'save'])->name('admin.user.save');
        Route::any('/users/delete', [User::class, 'delete'])->name('admin.user.delete');
        Route::post('/users/change_password', [User::class, 'change_password'])->name('change_password');

        Route::prefix('/roles')->group(function () {
            Route::get('/', [Role::class, 'index'])->name('admin.role.index');
            Route::get('/edit/{id}', [Role::class, 'edit'])->name('admin.role.edit');
            Route::get('/create', [Role::class, 'create'])->name('admin.role.create');
            Route::post('/save', [Role::class, 'save'])->name('admin.role.save');
            Route::any('/delete', [Role::class, 'delete'])->name('admin.role.delete');
        });

        Route::prefix('/banner')->group(function () {
            Route::get('/', [Banner::class, 'index'])->name('admin.banner.index');
            Route::get('/edit/{id}', [Banner::class, 'edit'])->name('admin.banner.edit');
            Route::get('/create', [Banner::class, 'create'])->name('admin.banner.create');
            Route::post('/save', [Banner::class, 'save'])->name('admin.banner.save');
            Route::any('/delete', [Banner::class, 'delete'])->name('admin.banner.delete');
        });

        Route::prefix('/banner-categories')->group(function () {
            Route::get('/', [BannerCategory::class, 'index'])->name('admin.banner.categories.index');
            Route::get('/edit/{id}', [BannerCategory::class, 'edit'])->name('admin.banner.categories.edit');
            Route::get('/create', [BannerCategory::class, 'create'])->name('admin.banner.categories.create');
            Route::post('/save', [BannerCategory::class, 'save'])->name('admin.banner.categories.save');
            Route::any('/delete', [BannerCategory::class, 'delete'])->name('admin.banner.categories.delete');
        });

        Route::prefix('/promotion-discount')->group(function () {
            Route::get('/', [DiscountCategory::class, 'index'])->name('admin.promotion.discount.index');
            Route::get('/edit/{id}', [DiscountCategory::class, 'edit'])->name('admin.promotion.discount.edit');
            Route::get('/create', [DiscountCategory::class, 'create'])->name('admin.promotion.discount.create');
            Route::post('/save', [DiscountCategory::class, 'save'])->name('admin.promotion.discount.save');
            Route::any('/delete', [DiscountCategory::class, 'delete'])->name('admin.promotion.discount.delete');
        });

        Route::prefix('/promotion-voucher')->group(function () {
            Route::get('/', [Voucher::class, 'index'])->name('admin.promotion.voucher.index');
            Route::get('/edit/{id}', [Voucher::class, 'edit'])->name('admin.promotion.voucher.edit');
            Route::get('/create', [Voucher::class, 'create'])->name('admin.promotion.voucher.create');
            Route::post('/save', [Voucher::class, 'save'])->name('admin.promotion.voucher.save');
            Route::any('/delete', [Voucher::class, 'delete'])->name('admin.promotion.voucher.delete');
        });

        Route::prefix('/filter')->group(function () {
            Route::get('/', [Filter::class, 'index'])->name('admin.filter.index');
            Route::get('/edit/{id}', [Filter::class, 'edit'])->name('admin.filter.edit');
            Route::get('/create', [Filter::class, 'create'])->name('admin.filter.create');
            Route::post('/save', [Filter::class, 'save'])->name('admin.filter.save');
            Route::any('/delete', [Filter::class, 'delete'])->name('admin.filter.delete');
        });
        Route::prefix('/filter-group')->group(function () {
            Route::get('/', [FilterGroup::class, 'index'])->name('admin.filter.group.index');
            Route::get('/edit/{id}', [FilterGroup::class, 'edit'])->name('admin.filter.group.edit');
            Route::get('/create', [FilterGroup::class, 'create'])->name('admin.filter.group.create');
            Route::post('/save', [FilterGroup::class, 'save'])->name('admin.filter.group.save');
            Route::any('/delete', [FilterGroup::class, 'delete'])->name('admin.filter.group.delete');
        });
        Route::prefix('/filter-category')->group(function () {
            Route::get('/', [FilterCategory::class, 'index'])->name('admin.filter.category.index');
            Route::get('/edit/{id}', [FilterCategory::class, 'edit'])->name('admin.filter.category.edit');
            Route::get('/create', [FilterCategory::class, 'create'])->name('admin.filter.category.create');
            Route::post('/save', [FilterCategory::class, 'save'])->name('admin.filter.category.save');
            Route::any('/delete', [FilterCategory::class, 'delete'])->name('admin.filter.category.delete');
        });
        Route::prefix('/filter-table')->group(function () {
            Route::get('/', [FilterTable::class, 'index'])->name('admin.filter.table.index');
            Route::get('/edit/{id}', [FilterTable::class, 'edit'])->name('admin.filter.table.edit');
            Route::get('/create', [FilterTable::class, 'create'])->name('admin.filter.table.create');
            Route::post('/save', [FilterTable::class, 'save'])->name('admin.filter.table.save');
            Route::any('/delete', [FilterTable::class, 'delete'])->name('admin.filter.table.delete');
        });

        Route::prefix('/recruitment')->group(function () {
            Route::get('/', [Recruitment::class, 'index'])->name('admin.recruitment.index');
            Route::get('/edit/{id}', [Recruitment::class, 'edit'])->name('admin.recruitment.edit');
            Route::get('/create', [Recruitment::class, 'create'])->name('admin.recruitment.create');
            Route::post('/save', [Recruitment::class, 'save'])->name('admin.recruitment.save');
            Route::any('/delete', [Recruitment::class, 'delete'])->name('admin.recruitment.delete');
        });

        Route::prefix('/comment')->group(function () {
            Route::prefix('/tour')->group(function () {
                Route::get('/', [ProductComment::class, 'index'])->name('admin.comment.tour.index');
                Route::get('/edit/{id}', [ProductComment::class, 'edit'])->name('admin.comment.tour.edit');
                Route::get('/create', [ProductComment::class, 'create'])->name('admin.comment.tour.create');
                Route::post('/save', [ProductComment::class, 'save'])->name('admin.comment.tour.save');
                Route::any('/delete', [ProductComment::class, 'delete'])->name('admin.comment.tour.delete');
            });
            Route::prefix('/news')->group(function () {});
        });


        Route::prefix('/menu-groups')->group(function () {
            Route::get('/', [MenuGroups::class, 'index'])->name('admin.menu.categories.index');
            Route::get('/edit/{id}', [MenuGroups::class, 'edit'])->name('admin.menu.categories.edit');
            Route::get('/create', [MenuGroups::class, 'create'])->name('admin.menu.categories.create');
            Route::post('/save', [MenuGroups::class, 'save'])->name('admin.menu.categories.save');
            Route::any('/delete', [MenuGroups::class, 'delete'])->name('admin.menu.categories.delete');
        });
        Route::prefix('/menu-items')->group(function () {
            Route::get('/', [MenuItems::class, 'index'])->name('admin.menu.index');
            Route::get('/edit/{id}', [MenuItems::class, 'edit'])->name('admin.menu.edit');
            Route::get('/create', [MenuItems::class, 'create'])->name('admin.menu.create');
            Route::post('/save', [MenuItems::class, 'save'])->name('admin.menu.save');
            Route::any('/delete', [MenuItems::class, 'delete'])->name('admin.menu.delete');
        });


        Route::prefix('/tours')->group(function () {
            Route::get('/', [Tour::class, 'index'])->name('admin.tour.index');
            Route::get('/edit/{id}', [Tour::class, 'edit'])->name('admin.tour.edit');
            Route::get('/create', [Tour::class, 'create'])->name('admin.tour.create');
            Route::post('/save', [Tour::class, 'save'])->name('admin.tour.save');
            Route::any('/delete', [Tour::class, 'delete'])->name('admin.tour.delete');
        });


        Route::prefix('/tour-categories')->group(function () {
            Route::get('/', [TourCategories::class, 'index'])->name('admin.tour.categories.index');
            Route::get('/edit/{id}', [TourCategories::class, 'edit'])->name('admin.tour.categories.edit');
            Route::get('/create', [TourCategories::class, 'create'])->name('admin.tour.categories.create');
            Route::post('/save', [TourCategories::class, 'save'])->name('admin.tour.categories.save');
            Route::any('/delete', [TourCategories::class, 'delete'])->name('admin.tour.categories.delete');
        });

        Route::prefix('/tour-types')->group(function () {
            Route::get('/', [TourTypes::class, 'index'])->name('admin.tour-types.index');
            Route::get('/edit/{id}', [TourTypes::class, 'edit'])->name('admin.tour-types.edit');
            Route::get('/create', [TourTypes::class, 'create'])->name('admin.tour-types.create');
            Route::post('/save', [TourTypes::class, 'save'])->name('admin.tour-types.save');
            Route::any('/delete', [TourTypes::class, 'deleteRecords'])->name('admin.tour-types.delete');
        });

        Route::prefix('/instructions')->group(function () {
            Route::get('/', [Instructions::class, 'index'])->name('admin.instructions.index');
            Route::get('/edit/{id}', [Instructions::class, 'edit'])->name('admin.instructions.edit');
            Route::get('/create', [Instructions::class, 'create'])->name('admin.instructions.create');
            Route::post('/save', [Instructions::class, 'save'])->name('admin.instructions.save');
            Route::any('/delete', [Instructions::class, 'deleteRecords'])->name('admin.instructions.delete');
        });
        Route::prefix('/experiential_style')->group(function () {
            Route::get('/', [Experiential_style::class, 'index'])->name('admin.experiential_style.index');
            Route::get('/edit/{id}', [Experiential_style::class, 'edit'])->name('admin.experiential_style.edit');
            Route::get('/create', [Experiential_style::class, 'create'])->name('admin.experiential_style.create');
            Route::post('/save', [Experiential_style::class, 'save'])->name('admin.experiential_style.save');
            Route::any('/delete', [Experiential_style::class, 'deleteRecords'])->name('admin.experiential_style.delete');
        });



        Route::prefix('/config')->group(function () {
            Route::get('/', [Config::class, 'index'])->name('admin.config.index');
            Route::post('/save', [Config::class, 'save'])->name('admin.config.save');
        });

        Route::prefix('/order')->group(function () {
            Route::get('/', [Order::class, 'index'])->name('admin.order.index');
            Route::get('/edit/{id}', [Order::class, 'edit'])->name('admin.order.edit');
            Route::post('/save', [Order::class, 'save'])->name('admin.order.save');
            Route::any('/delete', [Order::class, 'delete'])->name('admin.order.delete');
            Route::get('/send_fast', [Order::class, 'send_fast'])->name('send_fast');
        });

        Route::prefix('/news')->group(function () {
            Route::get('/', [News::class, 'index'])->name('admin.news.index');
            Route::get('/edit/{id}', [News::class, 'edit'])->name('admin.news.edit');
            Route::get('/create', [News::class, 'create'])->name('admin.news.create');
            Route::post('/save', [News::class, 'save'])->name('admin.news.save');
            Route::any('/delete', [News::class, 'delete'])->name('admin.news.delete');
        });

        Route::prefix('/news-categories')->group(function () {
            Route::get('/', [NewsCategories::class, 'index'])->name('admin.news.categories.index');
            Route::get('/edit/{id}', [NewsCategories::class, 'edit'])->name('admin.news.categories.edit');
            Route::get('/create', [NewsCategories::class, 'create'])->name('admin.news.categories.create');
            Route::post('/save', [NewsCategories::class, 'save'])->name('admin.news.categories.save');
            Route::any('/delete', [NewsCategories::class, 'delete'])->name('admin.news.categories.delete');
        });
        Route::prefix('/destination')->group(function () {
            Route::get('/', [Destination::class, 'index'])->name('admin.destination.index');
            Route::get('/edit/{id}', [Destination::class, 'edit'])->name('admin.destination.edit');
            Route::get('/create', [Destination::class, 'create'])->name('admin.destination.create');
            Route::post('/save', [Destination::class, 'save'])->name('admin.destination.save');
            Route::any('/delete', [Destination::class, 'delete'])->name('admin.destination.delete');
        });

        Route::prefix('/destination-categories')->group(function () {
            Route::get('/', [DestinationCategories::class, 'index'])->name('admin.destination.categories.index');
            Route::get('/edit/{id}', [DestinationCategories::class, 'edit'])->name('admin.destination.categories.edit');
            Route::get('/create', [DestinationCategories::class, 'create'])->name('admin.destination.categories.create');
            Route::post('/save', [DestinationCategories::class, 'save'])->name('admin.destination.categories.save');
            Route::any('/delete', [DestinationCategories::class, 'delete'])->name('admin.destination.categories.delete');
        });
        Route::prefix('/country')->group(function () {
            Route::get('/', [Country::class, 'index'])->name('admin.country.index');
            Route::get('/edit/{id}', [Country::class, 'edit'])->name('admin.country.edit');
            Route::get('/create', [Country::class, 'create'])->name('admin.country.create');
            Route::post('/save', [Country::class, 'save'])->name('admin.country.save');
            Route::any('/delete', [Country::class, 'delete'])->name('admin.country.delete');
        });

        Route::prefix('/city')->group(function () {
            Route::get('/', [City::class, 'index'])->name('admin.city.index');
            Route::get('/edit/{id}', [City::class, 'edit'])->name('admin.city.edit');
            Route::get('/create', [City::class, 'create'])->name('admin.city.create');
            Route::post('/save', [City::class, 'save'])->name('admin.city.save');
            Route::any('/delete', [City::class, 'delete'])->name('admin.city.delete');
        });

        Route::prefix('contact')->group(function () {
            Route::get('/', [Contact::class, 'index'])->name('admin.contact.index');
            Route::get('/edit/{id}', [Contact::class, 'edit'])->name('admin.contact.edit');
            Route::get('/delete', [Contact::class, 'delete'])->name('admin.contact.delete');
        });

        Route::prefix('/contents')->group(function () {
            Route::get('/', [Content::class, 'index'])->name('admin.contents.index');
            Route::get('/edit/{id}', [Content::class, 'edit'])->name('admin.contents.edit');
            Route::get('/create', [Content::class, 'create'])->name('admin.contents.create');
            Route::post('/save', [Content::class, 'save'])->name('admin.contents.save');
            Route::any('/delete', [Content::class, 'delete'])->name('admin.contents.delete');
        });

        Route::prefix('/vouchers')->group(function () {
            Route::get('/', [Voucher::class, 'index'])->name('admin.vouchers.index');
            Route::get('/edit/{id}', [Voucher::class, 'edit'])->name('admin.vouchers.edit');
            Route::get('/create', [Voucher::class, 'create'])->name('admin.vouchers.create');
            Route::post('/save', [Voucher::class, 'save'])->name('admin.vouchers.save');
            Route::any('/delete', [Voucher::class, 'delete'])->name('admin.vouchers.delete');
        });

        Route::prefix('/contents-categories')->group(function () {
            Route::get('/', [ContentCategories::class, 'index'])->name('admin.contents.categories.index');
            Route::get('/edit/{id}', [ContentCategories::class, 'edit'])->name('admin.contents.categories.edit');
            Route::get('/create', [ContentCategories::class, 'create'])->name('admin.contents.categories.create');
            Route::post('/save', [ContentCategories::class, 'save'])->name('admin.contents.categories.save');
            Route::any('/delete', [ContentCategories::class, 'delete'])->name('admin.contents.categories.delete');
        });


        Route::prefix('/store')->group(function () {
            Route::get('/', [Store::class, 'index'])->name('admin.store.index');
            Route::get('/edit/{id}', [Store::class, 'edit'])->name('admin.store.edit');
            Route::get('/create', [Store::class, 'create'])->name('admin.store.create');
            Route::post('/save', [Store::class, 'save'])->name('admin.store.save');
            Route::any('/delete', [Store::class, 'delete'])->name('admin.store.delete');
        });

        Route::prefix('/translation')->group(function () {
            Route::get('/', [Translation::class, 'index'])->name('admin.translation.index');
            Route::get('/edit/{id}', [Translation::class, 'edit'])->name('admin.translation.edit');
            Route::get('/create', [Translation::class, 'create'])->name('admin.translation.create');
            Route::post('/save', [Translation::class, 'save'])->name('admin.translation.save');
            Route::any('/delete', [Translation::class, 'delete'])->name('admin.translation.delete');
        });

        Route::prefix('/campaign')->group(function () {
            Route::get('/', [Campaign::class, 'index'])->name('admin.campaign.index');
            Route::get('/edit/{id}', [Campaign::class, 'edit'])->name('admin.campaign.edit');
            Route::get('/create', [Campaign::class, 'create'])->name('admin.campaign.create');
            Route::post('/save', [Campaign::class, 'save'])->name('admin.campaign.save');
            Route::any('/delete', [Campaign::class, 'delete'])->name('admin.campaign.delete');
        });

        Route::prefix('/members')->group(function () {
            Route::get('/', [Member::class, 'index'])->name('admin.member.index');
            Route::get('/edit/{id}', [Member::class, 'edit'])->name('admin.member.edit');
            Route::get('/create', [Member::class, 'create'])->name('admin.member.create');
            Route::post('/save', [Member::class, 'save'])->name('admin.member.save');
            Route::any('/delete', [Member::class, 'delete'])->name('admin.member.delete');
            Route::get('/vouchers', [Member::class, 'getVouchers'])->name('admin.member.vouchers');
            Route::post('/members/change_password', [Member::class, 'member_change_password'])->name('member_change_password');
        });
    });
});
