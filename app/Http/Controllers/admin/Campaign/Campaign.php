<?php

namespace App\Http\Controllers\admin\Campaign;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Campaign\Campaign as CampaignModel;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;
use App\Models\admin\Product\ProductCategories as Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use App\Models\admin\Product\ProductCampaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Campaign extends BaseController
{
    public function __construct()
    {
        $this->model = CampaignModel::class;
        $this->view = 'admin.campaign';
        $this->prefix = 'campaign';
    }

    public function index()
    {
        $campaigns = CampaignModel::where('published', 1)->get();
        foreach ($campaigns as $campaign) {
            if ($campaign->end_date < now()) {
                $campaign->published = 0;
                $campaign->save();
            }
        }

        return parent::index();
    }
    public function create()
    {
        $product_categories = ProductCategoriesModel::all();
        $list_tree = $this->indentRows2($product_categories);

        parent::setData([
            'categories' => $list_tree,
        ]);

        return parent::create();
    }

    public function edit($id)
    {
        $product_categories = ProductCategoriesModel::all();
        $list_tree = $this->indentRows2($product_categories);
        $products_campaign = ProductCampaign::where('campaign_id', $id)->with('product')->get();

        // dd($products_campaign);
        parent::setData([
            'categories' => $list_tree,
            'products_campaign' => $products_campaign
        ]);
        
        return parent::edit($id);
    }

    public function save(Request $request)
    {
        $data = $request->all();
        if ($data['discount_unit'] == 0) {
            return back()->withErrors([
                'Bạn chưa chọn đơn vị tính',
            ]);
        }
        if(isset($data['flash_sale'])){
            $data['flash_sale'] = 1;
        }else{
            $data['flash_sale'] = 0;
        }
        if ($data['applicable_platform'] == 0) {
            return back()->withErrors([
                'Bạn chưa chọn nền tảng áp dụng',
            ]);
        }
        if (!empty($data['categories'])) {
            $data['categories'] = implode(',', $data['categories']);
        } else {
            $data['categories'] = '';
        }

        if ($request->hasFile('discount_file')) {
            $file = $request->file('discount_file');
            $campaign_id = $data['id'];
            $extension = $file->getClientOriginalExtension();

            if ($extension !== 'xlsx' && $extension !== 'xls') {
                return back()->withErrors(['error' => 'Tệp phải có định dạng .xlsx hoặc .xls']);
            }
            try {
                $this->import($campaign_id, $file);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Không thể lưu được']);
            }
        }
        unset($data['discount_file']);
        parent::setData($data);
        return parent::save($request);
    }

    public function import($campaign_id, $file)
    {
        DB::table('products_campaign')->where('campaign_id', $campaign_id)->delete();

        Excel::import(new ProductImport($campaign_id), $file);

    }

    public function setRules()
    {
        return [
            'start_date' => 'required',
            'end_date' => 'required',
            'applicable_platform' => 'required'
        ];
    }
    public function setCustomMessages()
    {
        return [
            'start_date.required' => 'Bạn chưa chọn ngày bắt đầu',
            'end_date.required' => 'Bạn chưa chọn ngày kết thúc'
        ];
    }
}
