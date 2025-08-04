<?php

namespace App\Http\Controllers\client\Compare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Product\Product;
use App\Models\admin\Filter\FilterTable as FilterTableModel;
use App\Models\admin\Filter\Filter as FilterModel;
use Illuminate\Support\Facades\Session;

class Compare extends Controller
{
    public $compare;
    public $tableId;

    public function init()
    {
        $this->compare = Session::get('compare', collect([]));
        $this->tableId = Session::get('tableId', null);
    }

    public function index()
    {
        $this->init();
  
        $breadcrumbs = [
            (object) [
                'name' => 'So sánh sản phẩm', 
            ]
        ];

        $groupFilter = collect([]);
        $filterTable = FilterTableModel::where('id', $this->tableId)->with([
            'items' => function ($query) {
                $query->with(['category' => function ($query2) {
                    $query2->with('group');
                }]);
            }
        ])->first();

        if ($filterTable) {
            $filterCategory_id = $filterTable->items->pluck('filter_category_id');
            $filterSelectAvailable = FilterModel::whereIn('filter_category_id', $filterCategory_id)->get();

            $filters = $filterTable->items;
            $groupFilter = $filters->groupBy('category.group.name');
           
            $filterValues = [];
            $filterOutStanding = collect([]);

            foreach ($this->compare as $p => $product) {
                foreach ($product->filters as $filter) {
                    $filterValues[$p][$filter->filter_category_id] = $filter->value;
                }
            } 
         
            foreach ($groupFilter as $groupName => $group) {
                foreach ($group as $itemGroup) {
                    $itemGroup->value = collect([]);
                    foreach ($filterValues as $v => $value) {
                        $itemGroupValue = null;
                        // dd($value;)
                        if (isset($value[$itemGroup->filter_category_id])) {
                            $itemGroupValue = $value[$itemGroup->filter_category_id];
                        }

                        if (@$itemGroup->category->type == 'single') {
                            $itemGroupValue = @$filterSelectAvailable->where('id', $itemGroupValue)->first()->name ?: null;
                        }
                        if (@$itemGroup->category->type == 'multiple') {
                            $multipleValue = explode(',', $itemGroupValue);
                            $itemGroupValue = implode(', ', $filterSelectAvailable->whereIn('id', $multipleValue)->pluck('name')->toArray());
                        }

                        $itemGroup->value[] = $itemGroupValue; 
                    }

                    if ($itemGroup->is_outstanding) {
                        $filterOutStanding->push($itemGroup);
                    }
                }
            }

            // foreach ($groupFilter as $groupName => $group) {
            //     foreach ($group as $itemGroupIndex => $itemGroup) {
            //         foreach ($itemGroup->value as $itemGroupValue) {
            //             if (!$itemGroupValue->value || $itemGroupValue->value == '<p>&nbsp;</p>') {
            //                 $itemGroup->forget($itemGroupIndex);
            //             }
            //         } 
            //     }
            // }

            // foreach ($groupFilter as $groupName => $group) {
            //     if ($group->isEmpty()) {
            //         $groupFilter->forget($groupName);
            //     }
            // }

            if ($filterOutStanding->isNotEmpty()) {
                $groupFilter = $groupFilter->prepend($filterOutStanding, 'Thông số nổi bật');
            } 
        } 


        return view('client.compare.index', [
            'compare' => $this->compare,
            'breadcrumbs' => $breadcrumbs,
            'groupFilter' => $groupFilter,
        ]);
    }

    public function add(Request $request)
    {
        $id = $request->id;
        
        $this->init();
        
        if ($this->compare->count() === 3) {
            return response()->json([
                'error' => true,
                'message' => 'Vui lòng xóa bớt sản phẩm để tiếp tục so sánh!'
            ]);
        }

        if (in_array($id, $this->compare->pluck('id')->toArray())) {
            return response()->json([
                'error' => true,
                'message' => 'Sản phẩm đã tồn tại trong danh sách so sánh!'
            ]);
        }

        $product = Product::select('id', 'name', 'alias', 'image', 'category_id')
            ->where('id', $id)
            ->where('published', 1)
            ->with('category')
            ->first();

        if (!$product) {
            return response()->json([
                'error' => true,
                'message' => 'Sản phẩm không tồn tại!'
            ]);
        }

        $tableCompareId = $product->category->filter_table_id;

        if ($this->tableId && $tableCompareId != $this->tableId) {
            return response()->json([
                'error' => true,
                'message' => 'Chỉ có thể so sánh sản phẩm cùng nhóm hàng!'
            ]);
        }
       
        $compare = $this->compare;
        $compare->push($product);
        $compare = $compare->values();

        Session::put('compare', $compare);
        Session::put('tableId', $tableCompareId);
        

        return response()->json([
            'error' => false,
            'message' => 'success',
            'btn_bottom' => view('components.client.compare.btn-bottom')->render(),
            'list_bottom' => view('components.client.compare.list-bottom')->render(),
            'btn_offcanvas' => view('components.client.compare.btn-offcanvas')->render(),
            'list_offcanvas' => view('components.client.compare.list-offcanvas')->render(),
            // 'data' => Session::get('compare')
        ]);
    }

    public function remove(Request $request)
    {
        $id = $request->id;
        $this->init();

        if (!$id) {
            Session::forget('compare');
            Session::forget('tableId');
        } else {
            $compare = $this->compare;
            $compare = $compare->filter(function ($product) use ($id) {
                return $product->id != $id;
            });

            $compare = $compare->values();
            Session::put('compare', $compare);

            if (count($compare) == 0) {
                Session::forget('tableId');
                Session::forget('compare');
            }
        }

        return response()->json([
            'error' => false,
            'message' => 'success',
            'btn_bottom' => view('components.client.compare.btn-bottom')->render(),
            'list_bottom' => view('components.client.compare.list-bottom')->render(),
            'btn_offcanvas' => view('components.client.compare.btn-offcanvas')->render(),
            'list_offcanvas' => view('components.client.compare.list-offcanvas')->render(),
            // 'data' => Session::get('compare')
        ]);
    }

    public function search(Request $request)
    {
        $this->init();
        $keyword = $request->keyword;

        $products = Product::where('name', 'like', '%' . $keyword . '%')
            ->whereNotIn('id', $this->compare->pluck('id')->toArray())
            ->orWhere('alias', 'like', '%' . $keyword . '%')
            ->orWhereHas('attributes', function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%');
            })
            // ->whereHas('category', function ($query) {
            //     $query->where('filter_table_id', $this->tableId);
            // })
            ->where('published', 1)
            ->with([
                'attributes' => function ($query) {
                    $query->where('published', 1)->where('price', '>', 0);
                }
            ])
            ->orderBy('ordering', 'asc')
            ->paginate(50);

        $html = '';
        foreach ($products as $product) {
            $html .= view('components.client.product-item', ['item' => $product])->render();
        }

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $html
        ]);
    }
}
