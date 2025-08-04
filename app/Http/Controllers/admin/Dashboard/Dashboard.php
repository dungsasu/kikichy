<?php

namespace App\Http\Controllers\admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\admin\User\User as UserModel;
use App\Models\admin\Order\Order;
use App\Models\admin\Product\Product;
use App\Models\admin\Product\ProductCategories;
use App\Models\admin\Member\Member;
use App\Models\admin\Content\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Controller 
{
    public function index()
    {
        $list_user = UserModel::all();

        $totalOrders = Order::count();
        $products = Product::count();
        $totalPosts = Content::count();
        $total_members = Member::count();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $month_Orders = Order::whereMonth('created_at', $currentMonth)
                             ->whereYear('created_at', $currentYear)
                             ->count();
        
        $month_Products = Product::whereMonth('created_at', $currentMonth)
                                 ->whereYear('created_at', $currentYear)
                                 ->count();
        
        $month_Posts = Content::whereMonth('created_at', $currentMonth)
                              ->whereYear('created_at', $currentYear)
                              ->count();
        
        $month_Member = Member::whereMonth('created_at', $currentMonth)
                               ->whereYear('created_at', $currentYear)
                               ->count();

        // Lấy dữ liệu doanh thu theo 12 tháng của năm hiện tại
        $revenueData = [];
        $monthLabels = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthLabels[] = 'T' . $month;
            
            $revenue = Order::whereMonth('created_at', $month)
                           ->whereYear('created_at', $currentYear)
                           ->sum('total_price');
            $revenueData[] = $revenue ? (float)$revenue : 0;
        }

        // Lấy dữ liệu thực từ bảng product_categories và products
        $categoryData = DB::table('product_categories as pc')
            ->leftJoin('products as p', 'pc.id', '=', 'p.category_id')
            ->select('pc.name', DB::raw('COUNT(fs_p.id) as product_count'))
            ->where('pc.published', 1)
            ->groupBy('pc.id', 'pc.name')
            ->orderBy('product_count', 'desc')
            ->limit(10)
            ->get();

        $categoryNames = $categoryData->pluck('name')->toArray();
        $categoryCounts = $categoryData->pluck('product_count')->toArray();

        // Lấy dữ liệu tăng trưởng thành viên theo 12 tháng của năm hiện tại
        $memberGrowthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $memberCount = Member::whereMonth('created_at', $month)
                                  ->whereYear('created_at', $currentYear)
                                  ->count();
            $memberGrowthData[] = $memberCount;
        }

        $this->data = [
            'list_user' => $list_user,
            'totalOrders' => $totalOrders,
            'products' => $products, 
            'totalPosts' => $totalPosts,
            'total_members' => $total_members,
            'month_Orders' => $month_Orders,
            'month_Products' => $month_Products,
            'month_Posts' => $month_Posts,
            'month_Member' => $month_Member,
            'revenueData' => $revenueData,
            'monthLabels' => $monthLabels,
            'categoryNames' => $categoryNames,
            'categoryCounts' => $categoryCounts,
            'memberGrowthData' => $memberGrowthData
        ];
        return view('admin.dashboard', $this->data);
    }
}
