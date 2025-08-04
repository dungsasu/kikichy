<?php

namespace App\Http\Controllers\admin\Locale;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Locale extends BaseController
{
    public function changeLocale($locale)
    {
        App::setLocale($locale);
        Session::put('locale', $locale);
        return redirect()->back();
    }
}
