<?php

namespace App\Http\Controllers;

use AWS\CRT\HTTP\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request as HttpRequest;
use App\Models\admin\Order\Order;
use App\Models\admin\News\News;
use App\Models\admin\Product\Product;
use App\Models\admin\Member\Member;
use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function permission() {
        return view('admin.permission');
    }

    public function dashboard() {
        $totalOrders = Order::get()->count();
        $month_Orders = Order::whereMonth('created_at', now()->month)->count();

        $totalPosts = News::get()->count();
        $month_Posts = News::whereMonth('created_at', now()->month)->count();

        $products = Product::get()->count();
        $month_Products = Product::whereMonth('created_at', now()->month)->count();

        $total_members = Member::get()->count();
        $month_Member = Member::whereMonth('created_at', now()->month)->count();
        // dd($products);
        $this->setData([
            'totalOrders' => $totalOrders,
            'month_Orders' => $month_Orders,
            'totalPosts' => $totalPosts,
            'month_Posts' => $month_Posts,
            'products' => $products,
            'month_Products' => $month_Products,
            'total_members' => $total_members,
            'month_Member' => $month_Member
        ]);

        return view('admin.dashboard', $this->data);
    }

    public function getProductBelongCategory($id) {
        
    }
}
