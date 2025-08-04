<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Traits\Tree;

class MenuServiceProvider extends ServiceProvider
{
  use Tree;
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);

    $createLinks = file_get_contents(base_path('resources/menu/createLink.json'));
    $tree = $this->printTreeJsonCreateLink($createLinks);

    // Share all menuData to all the views
    View::share('menuData', [$verticalMenuData]);
    View::share('treeLink', $tree);

  }
}
