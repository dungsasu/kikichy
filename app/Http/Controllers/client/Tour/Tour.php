<?php

namespace App\Http\Controllers\client\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Tour\Tour as TourModel;
use App\Models\admin\Tour\TourCategories as TourCategoriesModel;
use App\Services\Fast\FastService;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Facebook\FacebookPixel;

class Tour extends Controller
{
    protected $facebookPixel;

    function __construct()
    {
    }

    private $message = [
        'search.empty' => 'Bạn chưa nhập từ khoá tìm kiếm',
        'tour.notfound' => 'Tour không tồn tại',
    ];

    public function detail($category, $alias)
    {
        $tour = TourModel::where('alias', $alias)->where('published', 1)
            ->with([
                'images',
                'sizes',
                'colors' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                },
                'category',
            ])
            ->first();

        if (!$tour) {
            abort(404, $this->message['tour.notfound']);
        }

        return view('client.tour.detail', compact('tour'));
    }
}
