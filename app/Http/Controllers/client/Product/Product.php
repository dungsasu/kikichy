<?php

namespace App\Http\Controllers\client\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Product\Product as ProductModel;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;
use App\Models\admin\Product\ProductColorImage;
use App\Services\Fast\FastService;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Facebook\FacebookPixel;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class Product extends Controller
{
    protected $facebookPixel;

    function __construct()
    {
    }


    private $message = [
        'search.empty' => 'Bạn chưa nhập từ khoá tìm kiếm',
        'product.notfound' => 'Sản phẩm không tồn tại',

    ];
    public function detail($category, $alias)
    {
        
        $product = ProductModel::where('alias', $alias)->where('published', 1)
            ->with([
                'images',
                'sizes',
                'reviews' => function ($query) {
                    $query->with('member');
                    $query->where('published', 1);
                    $query->orderBy('created_at', 'desc');
                },
                'colors' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                },
                'category',
                'colors_ordering'
            ])
            ->first();

            
        if (!@$product->id) {
            return redirect()->route('client.home')->with(['message' => $this->message['product.notfound'], 'status' => 'error']);
        }
        $averageRating = $product->reviews->avg('rating');
        $product->averageRating = round($averageRating, 1);
        $data = $product ? $product->toArray() : null;
        $data['price'] = $product->price_campain ?? $product->price;
        $data['price_old'] = $product->price_old_campain ?? $product->price_old;
        
        $data = (object)$data;
        $product_id = $data->id;
        $colors = [];
        $sizes = [];
        $orderingMap = [];


        foreach ($data->colors_ordering as $color_ordering) {
            $orderingMap[$color_ordering['alias']] = $color_ordering['pivot']['ordering'];
        }

        foreach ($data->colors as $key => $item) {
            $temp = ProductColorImage::where('color_id', $item['color_id'])
                ->where('product_id', $product_id)
                ->get();
            $itemArray = (array)$item;
            $itemArray['images'] = $temp;
            $colors[$key] = (object)$itemArray;
        }

        usort($colors, function($a, $b) use ($orderingMap) {
            $orderA = $orderingMap[$a->alias] ?? PHP_INT_MAX;
            $orderB = $orderingMap[$b->alias] ?? PHP_INT_MAX;
            return $orderA <=> $orderB;
        });

        foreach($colors as $item) {
            $item->alias = strtoupper($item->alias);
        }
        
        foreach ($data->sizes as $key => $item) {
            $sizes[$key] = (object)$item;
        }

        $data->product_related = ProductModel::whereIn('id', explode(',', $data->product_related))->get();
        $breadcrumbs =  ProductCategoriesModel::whereIn('id', explode(',', $data->category_id_wrapper))->get();


        if ($data == null)
            return redirect()->route('client.home');

        $this->viewed_product($data->id);

        $viewedProducts = session()->get('viewed_products', []);

        $viewedProducts = ProductModel::whereIn('id', $viewedProducts)
            ->where('published', 1)
            ->get();

        $products_in_categories = ProductModel::where('category_id', $data->category_id)
            ->where('published', 1)
            ->where('id', '!=', $data->id)
            ->limit(10)
            ->get();
        $availableColorsSizes = $this->product_fast($data);

        // Fix: Get available sizes for the first color properly
        $firstColorAvailableSizes = [];
        if (!empty($colors) && count($colors) > 0) {
            $firstColor = $colors[0];
            $firstColorAvailableSizes = $availableColorsSizes[$firstColor->alias] ?? [];
        }


        // dd($category);
        $facebookPixel = new FacebookPixel(Auth::guard('members')->user());
        $facebookPixel->viewContentProduct($product, $category);


        // dd($firstColorAvailableSizes);die;
        // dd(count($availableColorsSizes[$colors[0]->alias]) > 0);
        return view('client.product.index', [
            'data' => $data,
            'colors' => $colors,
            'sizes' => $sizes,
            'viewedProducts' => $viewedProducts,
            'breadcrumbs' => $breadcrumbs,
            'products_in_categories' => $products_in_categories,
            'availableColorsSizes' => $availableColorsSizes,
            'firstColorAvailableSizes' => $firstColorAvailableSizes,
            'category_alias' => $category
        ]);
    }

    public function searchProduct()
    {
        $product = urldecode(request()->keyword);


        if (!$product) {
            return redirect(route("client.home"))->with(['message' => $this->message['search.empty'], 'status' => 'error']);
        }
        $keywords = explode(' ', $product);
        $regexp = join('|', array_map('preg_quote', $keywords));

        $exactMatches = ProductModel::where(function ($query) use ($product) {
            $query->where('name', 'like', '%' . $product . '%')
                  ->orWhere('code', $product);
        })
        ->where('published', 1)
        ->take(10)
        ->get();
        
        if ($exactMatches->isEmpty()) {
            $products = ProductModel::whereRaw("name REGEXP ?", [$regexp])->where('published', 1)->get();
        } else {
            $products = $exactMatches;
        }

        
        $facebookPixel = new FacebookPixel(Auth::guard('members')->user());
        $facebookPixel->trackSearch($product, $products);


        return view('client.product.search', [
            'products' => $products,
            'keyword' => $product
        ]);
    }
    public function sale_product()
    {
        return view('client.product.sale');
    }
    public function check_color_size($id, $color, $size)
    {
        $list = DB::table('products_fast')->where('product_id', $id)->where('color_name', $color)->where('size_name', $size)->get();
        return count($list) > 0;
    }

    public function product_fast($data)
    {
        $list = DB::table('products_fast')
            ->where(function ($query) use ($data) {
                $query->where('product_id', $data->id)
                    ->orWhere('code_prd', 'like', '%' . $data->code . '%');
            })
            ->where('quantity', '>', 0)
            ->get();
        $array = [];

        foreach ($list as $item) {
            $array[$item->color_name][] = $item->size_name;
        }
        return $array;
    }
    public function viewed_product($id)
    {
        $product = ProductModel::find($id);
        $viewedProducts = session()->get('viewed_products', []);
        if (!in_array($product->id, $viewedProducts)) {
            $viewedProducts[] = $product->id;
        }
        session()->put('viewed_products', $viewedProducts);
    }
    public function getProductColorImages()
    {
        $product_id = request()->productId;
        $color_id = request()->colorId;
        $default_color = request()->defaultColor;

        $prod = ProductModel::with(['sizes', 'colors' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }, 'category'])->findOrFail($product_id);

        // Lấy ảnh theo màu
        $productColorImages = ProductColorImage::where('product_id', $product_id)
            ->where('color_id', $color_id)
            ->get();

        $slideView = view('client.partials.product_slide', [
            'data' => $productColorImages,
            'prod' => $prod,
            'default_color' => $default_color
        ])->render();

        // Lấy size theo màu
        $availableColorsSizes = $this->product_fast($prod);
        $data_color = $prod->colors->where('color_id', $color_id)->first();
        $availableSizes = $data_color ? ($availableColorsSizes[$data_color->alias] ?? []) : [];

        $sizeView = view('client.partials.size_available', [
            'sizes' => $prod->sizes,
            'availableSizes' => $availableSizes,
        ])->render();

        $buttonView = view('client.partials.button_add_cart', [
            'availableSizes' => $availableSizes,
            'prod' => $prod
        ])->render();

        $buttonBuyNowView = view('client.partials.button_buynow_cart', [
            'availableSizes' => $availableSizes,
            'prod' => $prod
        ])->render();


        return response()->json([
            'html' => $slideView,
            'html_size' => $sizeView,
            'html_button' => $buttonView,
            'html_button_buynow' => $buttonBuyNowView
        ]);
    }

    public function showDetailProduct($id)
    {
        $data = ProductModel::where('id', $id)
            ->with(['images', 'sizes', 'colors', 'category'])
            ->first();

        if (!$data) {
            return null;
        }

        $data = (object)$data;
        $product_id = $data->id;
        $colors = [];
        $sizes = [];

        $productColorImages = ProductColorImage::where('product_id', $product_id)->get()->groupBy('color_id');
        $colors = $data->colors->map(function ($item) use ($productColorImages) {
            $item->images = $productColorImages->get($item->color_id, collect());
            return $item;
        });

        foreach($colors as $item) {
            $item->alias = strtoupper($item->alias);
        }

        $sizes = $data->sizes;
        $data->product_related = ProductModel::whereIn('id', explode(',', $data->product_related))->get();
        $breadcrumbs =  ProductCategoriesModel::whereIn('id', explode(',', $data->category_id_wrapper))->get();
        $jsonView = '';

        $availableColorsSizes = $this->product_fast($data);
        if ($data) {
            $jsonView = view('client.partials.product_detail', [
                'data' => $data,
                'colors' => $colors,
                'sizes' => $sizes,
                'availableColorsSizes' => $availableColorsSizes
            ])->render();
        }

        return response()->json([
            'html' => $jsonView
        ]);
    }

    public function getGalleryComponent($id)
    {
        $html  = <<<HTML
            <div class="col-md-4 color-ordering">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" form="formAccountSettings" type="text" id="ordering" name="product_color_ordering$id">
                    <label for="ordering">Thứ tự</label>
                </div>
            </div>
        HTML;
        $html .= view('components.gallery', ['name' => 'gallery_color' . $id, 'field' => 'image', 'type' => 'Images'])->render();

        return response()->json(['html' => $html]);
    }

    public function send_comment()
    {
        $review = request()->input('review');
        $rating_star = request()->input('rating_star');
        $product_id = request()->input('product_id');
        $data_product = ProductModel::where('id', $product_id)->first();
        $category = ProductCategoriesModel::where('id', $data_product->category_id)->first();

        if (!$rating_star) {
            return redirect()->route('client.product', ['category' => $category->alias, 'alias' => $data_product->alias])->with(['message' => 'Vui lòng chọn đánh giá sao', 'status' => 'error']);
        }

        if (!Auth::guard('members')->check()) {
            return redirect()->route('client.product', ['category' => $category->alias, 'alias' => $data_product->alias])->with(['message' => 'Bạn cần đăng nhập để đánh giá sản phẩm này', 'status' => 'error']);;
        }
        $member_id = Auth::guard('members')->id();

        $member_exists = DB::table('members')->where('id', $member_id)->exists();

        if (!$member_exists) {
            return redirect()->route('client.product', ['category' => $category->alias, 'alias' => $data_product->alias])
                             ->with(['message' => 'Thành viên không tồn tại', 'status' => 'error']);
        }

        $hasPurchased = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.product_id', $product_id)
            ->exists();

        if (!$hasPurchased) {
            return redirect()->route('client.product', ['category' => $category->alias, 'alias' => $data_product->alias])
                             ->with(['message' => 'Bạn cần mua sản phẩm này trước khi đánh giá', 'status' => 'error']);
        }

        $data = [
            'product_id' => $product_id,
            'member_id' => $member_id,
            'rating' => $rating_star,
            'review' => $review,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        DB::table('product_reviews')->insert($data);


        return redirect()->route('client.product', ['category' => $category->alias, 'alias' => $data_product->alias])->with(['message' => 'Cảm ơn bạn đã đánh giá sản phẩm', 'status' => 'success']);
    }

    public function syncManual() {
        $products = DB::table('products_fast')->where('manual', null)->orWhere('manual', 0)->limit(100)->get();
        $fast_service = new FastService();
        if($products->isEmpty()) {
            DB::table('products_fast')->update(['manual' => 0]);
            return response()->json(['message' => 'Done!']);
        }
        foreach($products as $item) {
            $fast_service->getItemInventory($item->code_prd);
            DB::table('products_fast')->where('id', $item->id)->update(['manual' => 1]);
        }
        return response()->json(['message' => 'Đồng bộ thành công']);

    }
}
