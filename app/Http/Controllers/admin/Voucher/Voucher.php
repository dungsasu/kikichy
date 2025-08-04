<?php

namespace App\Http\Controllers\admin\Voucher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Voucher\Voucher as VoucherModel;
use App\Services\Fast\FastService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Voucher extends Controller
{
    private $fast;

    public function __construct()
    {
        $this->model = VoucherModel::class;
        $this->view = 'admin.vouchers';
        $this->prefix = 'vouchers';
        $this->fast = new FastService();
        $this->order_by = ['created_time', 'desc'];
        $this->searchField = 'code, customer, name';
    }

    public function index()
    {
        $year = now()->year;
        $tableName = 'vouchers_' . $year;
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->string('name')->nullable();
                $table->string('type')->nullable();
                $table->double('discount', 8, 0)->nullable();
                $table->date('date_start')->nullable();
                $table->date('date_expiration')->nullable();
                $table->string('department')->nullable();
                $table->string('department_group1')->nullable();
                $table->string('department_group2')->nullable();
                $table->string('department_group3')->nullable();
                $table->string('customer')->nullable();
                $table->string('customer_group1')->nullable();
                $table->string('customer_group2')->nullable();
                $table->string('customer_group3')->nullable();
                $table->double('bill_from', 11, 0)->nullable();
                $table->double('bill_to', 11, 0)->nullable();
                $table->integer('quantity_from')->nullable();
                $table->integer('quantity_to')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamp('created_time')->useCurrent();
                $table->boolean('ad_ckc_yn')->default(0);
                $table->boolean('ad_ckvip_yn')->default(0);
                $table->boolean('ad_cktang_yn')->default(0);
                $table->boolean('ad_ckcombo_yn')->default(0);
                $table->integer('ordering')->nullable();
                $table->integer('used')->nullable();
                $table->string('minuspoint')->nullable();
                $table->timestamps();
            });
        }
        return parent::index();
    }

    public function create()
    {
        parent::setData([
            'code' => $this->fast->createVoucherCode(),
            'members' => DB::table('members')->get()
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);
        
        // Get customer information if exists
        $selectedMember = null;
        if ($data->customer) {
            $selectedMember = DB::table('members')
                ->where('ma_kh', $data->customer)
                ->orWhere('ma_the', $data->customer)
                ->first();
        }
        
        parent::setData([
            'selectedMember' => $selectedMember
        ]);
        
        return parent::edit($id);
    }

    public function getMembers(Request $request)
    {
        $query = DB::table('members');

        if ($request->has('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->orWhere('ma_kh', 'like', '%' . $keyword . '%')
                    ->orWhere('ma_the', 'like', '%' . $keyword . '%');
            });
        }

        $members = $query->paginate(100);

        return response()->json($members);
    }

    public function save(Request $request)
    {
        $data = $request->all();
        
        // If editing existing voucher
        if (isset($data['id']) && $data['id']) {
            $model = VoucherModel::findOrFail($data['id']);
        } else {
            $model = new VoucherModel();
        }
        
        $model->name = $data['name'];
        $model->code = $data['code'];
        $model->type = $data['type'];
        
        // Handle customer selection
        if (isset($data['customer']) && $data['customer']) {
            $member = DB::table('members')->where('id', $data['customer'])->first();
            if ($member) {
                $model->customer = $member->ma_kh ?: $member->ma_the;
            }
        } else {
            $model->customer = null;
        }

        $model->date_start = $data['date_start'];
        $model->date_expiration = $data['date_expiration'];
        $model->discount = $data['discount'];
        $model->bill_from = isset($data['bill_from']) ? $data['bill_from'] : 0;
        $model->bill_to = isset($data['bill_to']) ? $data['bill_to'] : 0;
        
        // Handle checkboxes
        $model->ad_ckc_yn = isset($data['ad_ckc_yn']) ? 1 : 0;
        $model->ad_ckvip_yn = isset($data['ad_ckvip_yn']) ? 1 : 0;
        $model->ad_cktang_yn = isset($data['ad_cktang_yn']) ? 1 : 0;
        $model->ad_ckcombo_yn = isset($data['ad_ckcombo_yn']) ? 1 : 0;

        $model->save();
        
        // Create voucher in Fast service only for new vouchers
        if (!isset($data['id']) || !$data['id']) {
            $this->fast->createVoucherFast($model->code);
        }

        return redirect()->route('admin.vouchers.index');
    }
}
