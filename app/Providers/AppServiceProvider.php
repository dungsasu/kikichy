<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\Config\Config as ConfigModel;
use App\Models\admin\Menu\MenuItems as MenuItemsModel;
use App\Traits\CommonFunctionTrait;
use Illuminate\Routing\Router;


class AppServiceProvider extends ServiceProvider
{
    use CommonFunctionTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // dd($cartService->getDataCart());

        $discount_campaign = null;


        view()->composer('*', function ($view) {

            // dd($cartItems);
            $main_menu = MenuItemsModel::where('published', 1)->where('group_id', 3)->first();
            $footer_menu = MenuItemsModel::where('published', 1)->where('group_id', 4)->with('group')->first();
            // dd($footer_menu);

            $main_menu_data = json_decode(optional($main_menu)->description, true);
// dd($main_menu_data);
            // Sửa lại: Khởi tạo đúng kiểu
            $main_menu_html = '';
            $main_menu_mobile_html = '';
            $hot_images = [];

            if ($main_menu_data) {
                $main_menu_html = $this->generate_menu_html($main_menu_data);
                $main_menu_mobile_html = $this->buildMenu($main_menu_data);
                $hot_images = $this->findHotImages($main_menu_data);
            }
            if ($footer_menu) {
                $footer_menu = $this->generate_menu_footer_html($footer_menu);
            }

            $configs = ConfigModel::all();
            $temp = [];
            foreach ($configs as $config) {
                if ($config['alias'] == 'logo') {
                    $config['value'] = url($config['value']);
                }
                $temp[$config['alias']] = $config['value'];
            }

            $member_isSignedIn = Auth::guard('members')->check();
            $member = Auth::guard('members')->user();
            // dd($main_menu_html);
            $view->with('user', Auth::user())
                ->with('member_isSignedIn', $member_isSignedIn)
                ->with('member', $member)
                ->with('config', $temp)
                ->with('main_menu', $main_menu_html)
                ->with('main_menu_mobile_html', $main_menu_mobile_html)
                ->with('hot_images', $hot_images)
                ->with('footer_menu', $footer_menu);
        });

        Paginator::defaultView('pagination::bootstrap-4');
    }
}
