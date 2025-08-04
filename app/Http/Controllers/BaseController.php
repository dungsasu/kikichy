<?php

namespace App\Http\Controllers;

use App\Traits\CommonFunctionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\Tree;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use App\Models\admin\Product\ProductCategories as ProductCategoriesModel;
use function Psy\debug;

class BaseController
{
    use CommonFunctionTrait;
    use Tree;

    protected $model;
    protected $controller;
    protected $view;
    protected $prefix = '';
    protected $data = [];

    protected $searchField = 'name'; //lọc theo tên
    protected $categoryField = 'category_id'; //lọc theo danh mục
    protected $customFilters = [];

    protected $sizes_resize = []; //resize ảnh

    protected $limit = 50;
    protected $order_by = ['created_at', 'desc'];
    protected $with = '';
    protected $list;

    protected $translations_table = [];
    protected $translations_field = [];

    public function __construct($model = null, $view = null, $prefix = null)
    {
        $this->model = $model;
        $this->view = $view;
        $this->prefix = $prefix;
        $this->controller = self::class;
    }

    protected function setData($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function index()
    {
        $filter = request()->session()->get('filter');
        $query = $this->model::query();

        if (!empty($this->with)) {
            $query->with($this->with);
        }

        $this->applyFilters($query, $filter);

        if (!empty($this->data['list'])) {
            $list = $this->data['list'];
        } else {
            $list = $query->orderBy($this->order_by[0], $this->order_by[1])->paginate($this->limit);
        }

        $maxOrdering = @$this->model->ordering ? $this->model::max('ordering') : 0;
        $show_button_action = $this->checkPermissions();

        return view($this->view . '.index', array_merge($this->data, [
            'list' => $list,
            'prefix' => $this->prefix,
            'maxOrdering' => $maxOrdering,
            'view' => $this->view,
            'show_button_action' => $show_button_action,
            'model' => $this->model,
            'controller' => $this->controller,
            'filter' => $filter
        ]));
    }

    public function submit_filter()
    {
        $request_all = request()->input();
        foreach ($request_all as $key => $value) {
            if (is_null($value)) {
                $request_all[$key] = '';
            }
        }
        unset($request_all['_token']);
        if (request()->session()->has('filter')) {
            request()->session()->forget('filter');
        }

        request()->session()->put('filter', $request_all);

        return redirect()->back();
    }

    protected function applyFilters(&$query, $filter)
    {

        if ($filter && $this->prefix) {
            if (!empty($filter[$this->prefix . '_keyword_filter'])) {
                $searchFields = explode(',', $this->searchField);
                $query->where(function ($query) use ($searchFields, $filter) {
                    foreach ($searchFields as $field) {
                        $query->orWhere(trim($field), 'like', '%' . $filter[$this->prefix . '_keyword_filter'] . '%');
                    }
                });
            }

            if (!empty($filter[$this->prefix . '_category_id_filter'])) {
                $query->where($this->categoryField, '=', $filter[$this->prefix . '_category_id_filter']);
            }

            if (!empty($filter[$this->prefix . '_fromdate'])) {
                $query->where(function ($q) use ($filter) {
                    $tableName = $this->getTableName();
                    foreach ($this->dateFields as $field) {
                        if (Schema::hasColumn($tableName, $field)) {
                            $q->orWhere($field, '>=', $filter[$this->prefix . '_fromdate']);
                        }
                    }
                });
            }

            if (!empty($filter[$this->prefix . '_enddate'])) {
                $query->where(function ($q) use ($filter) {
                    $tableName = $this->getTableName();
                    foreach ($this->dateFields as $field) {
                        if (Schema::hasColumn($tableName, $field)) {
                            $q->orWhere($field, '<=', $filter[$this->prefix . '_enddate']);
                        }
                    }
                });
            }

            // Áp dụng các custom filters
            $this->applyCustomFilters($query, $filter);

            // Áp dụng range filters
            $this->applyRangeFilters($query, $filter);
        }
    }

    protected function applyRangeFilters(&$query, $filter)
    {
        if (!empty($this->customFilters) && $filter && $this->prefix) {
            foreach ($this->customFilters as $filterConfig) {
                if (isset($filterConfig['type']) && $filterConfig['type'] === 'number_range') {
                    $field = $filterConfig['field'];
                    $minKey = $this->prefix . '_' . $field . '_min_filter';
                    $maxKey = $this->prefix . '_' . $field . '_max_filter';

                    if (!empty($filter[$minKey])) {
                        $query->where($field, '>=', $filter[$minKey]);
                    }

                    if (!empty($filter[$maxKey])) {
                        $query->where($field, '<=', $filter[$maxKey]);
                    }
                }
            }
        }
    }

    protected function applyCustomFilters(&$query, $filter)
    {
        if (!empty($this->customFilters) && $filter && $this->prefix) {
            foreach ($this->customFilters as $filterConfig) {
                $filterKey = $this->prefix . '_' . $filterConfig['field'] . '_filter';
                if (isset($filter[$filterKey]) && $filter[$filterKey] !== '') {
                    $operator = $filterConfig['operator'] ?? '=';
                    $field = $filterConfig['field'];
                    $value = $filter[$filterKey];

                    // Xử lý trường hợp filter với null
                    if ($value === 'null') {
                        $query->whereNull($field);
                        continue;
                    }

                    switch ($operator) {
                        case 'like':
                            $query->where($field, 'like', '%' . $value . '%');
                            break;
                        case 'in':
                            if (is_array($value)) {
                                $query->whereIn($field, $value);
                            } else {
                                $query->where($field, '=', $value);
                            }
                            break;
                        case 'not_in':
                            if (is_array($value)) {
                                $query->whereNotIn($field, $value);
                            } else {
                                $query->where($field, '!=', $value);
                            }
                            break;
                        case 'between':
                            if (is_array($value) && count($value) == 2) {
                                $query->whereBetween($field, $value);
                            }
                            break;
                        case 'null':
                            if ($value == '1') {
                                $query->whereNull($field);
                            } elseif ($value == '0') {
                                $query->whereNotNull($field);
                            }
                            break;
                        default:
                            $query->where($field, $operator, $value);
                            break;
                    }
                }
            }
        }
    }

    protected function checkPermissions()
    {
        $user = Auth::user();
        $permissions = $user->rolePermission->toArray();
        foreach ($permissions as $permission) {
            if (strpos($permission['route'], $this->view . '.index.edit') !== false) {
                return $permission['permission'];
            }
        }
        return 0;
    }

    public function create()
    {
        $maxOrdering = @$this->model->ordering ? ($this->model::max('ordering') + 1 ?? 1) : 0;
        return view($this->view . '.form', array_merge($this->data, [
            'maxOrdering' => $maxOrdering,
            'view' => $this->view
        ]));
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);

        return view($this->view . '.form', array_merge($this->data, [
            'data' => $data,
            'view' => $this->view
        ]));
    }

    public function save(Request $request)
    {
        $data = $this->data ? $this->data : $request->all();
        $data = @$data['info'] ?? $data;

        if (isset($data['parent_id']) && $data['parent_id'] == "0") {
            $data['parent_id'] = null;
        } else if (isset($data['parent_id'])) {
            if ($data['id']) {
                if ($this->checkParentId($data['parent_id'], $data['id'])) {
                    return back()->withErrors([
                        'Danh mục cha không hợp lệ',
                    ]);
                }
            }
        }

        $shouldRedirect = $data['shouldRedirect'] ?? 1;
        unset($data['_token']);
        unset($data['shouldRedirect']);
        $this->data = $data;

        $fields = [];

        foreach ($data as $key => $item) {
            $fields[] = $key;
        }

        $rules = $this->setRules();
        $customMessages = $this->setCustomMessages();

        $id = $this->store($request, $fields, $rules, $customMessages);

        $this->save_extend($id);

        $redirect = $this->setRedirect();

        if ($shouldRedirect == '3') {
            $redirect = isset($redirect[2]) ? $redirect[2] : $this->view . '.index';
            return redirect()->route($redirect)
                ->with('success', 'Lưu bản ghi thành công');
        }
        if ($shouldRedirect == '2') {
            return redirect()->route($redirect[0])
                ->with('success', 'Lưu bản ghi thành công');
        }
        if ($shouldRedirect == '1') {
            return redirect()->route($redirect[1], ['id' => $id])
                ->with('success', 'Lưu bản ghi thành công');
        }
    }

    public function checkParentId($new_parent_id, $id)
    {
        if ($new_parent_id == $id) {
            return true;
        }
        $category = $this->model::find($new_parent_id);
        if (!$category) {
            return false;
        }
        if ($category->parent_id == $id) {
            return true;
        }
        return $this->checkParentId($category->parent_id, $id);
    }

    protected function setRules()
    {
        return [];
    }

    protected function setCustomMessages()
    {
        return [];
    }

    protected function setRedirect()
    {
        return [$this->view . '.create', $this->view . '.edit', $this->view . '.index'];
    }

    protected function save_extend($id) {}

    public function store($request, $fields = array(), $rules = array(), $customMessages = array(), $redirect = null)
    {
        if (!empty($this->data['other_images'])) {
            $otherImages = $this->data['other_images'];
            unset($this->data['other_images']);
        }
        // if ($request->has('info') && is_array($request->info)) {
        //     $validator = Validator::make($request->info, $rules, $customMessages);
        //     dd($validator);
        //     if ($validator->fails()) {
        //         return redirect()->back()->withErrors($validator)->withInput();
        //     }
        // } else {
        $request->validate($rules, $customMessages);
        // } 

        if (in_array('id', $fields) && isset($this->data['id'])) {
            $instance = $this->model::find($this->data['id']);
            if (!$instance) {
                return redirect()->route($redirect)
                    ->withErrors('Không tìm thấy bản ghi');
            }
        } else {
            $instance = new $this->model;
        }

        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'password':
                    if (isset($this->data['password'])) {
                        $instance->$key = Hash::make($value);
                    }
                    break;
                case 'alias':
                    if (array_key_exists('alias', $this->data)) {
                        $alias = @$this->data['alias'] ? $this->generate_alias(@$this->data['alias']) : (@$this->data['name'] ? $this->generate_alias(@$this->data['name']) : $this->generate_alias(@$this->data['title']));
                        $originalAlias = $alias;
                        $latestAlias = $this->model::where('alias', 'LIKE', $originalAlias . '%')
                            ->orderBy('alias', 'desc')
                            ->first();

                        if ($latestAlias) {
                            if (preg_match('/-(\d+)$/', $latestAlias->alias, $matches)) {
                                $counter = (int) $matches[1] + 1;
                            } else {
                                $counter = 1;
                            }
                        } else {
                            $counter = 1;
                        }
                        if ($this->data['id']) {
                            $count = $this->model::where('alias', $alias)->where('id', '!=', $this->data['id'])->count();
                        } else {
                            $count = $this->model::where('alias', $alias)->count();
                        }
                        if ($count >= 1) {
                            $alias = $originalAlias . '-' . $counter;
                            $counter++;
                        }
                        $instance->$key = $alias;
                    }
                    break;
                case 'ordering':
                    // Xử lý đặc biệt cho ordering để tránh null
                    if ($value === null || $value === '' || !is_numeric($value)) {
                        // Kiểm tra nếu đang edit thì giữ nguyên ordering cũ
                        if (isset($this->data['id']) && $this->data['id']) {
                            $oldRecord = $this->model::find($this->data['id']);
                            if ($oldRecord && $oldRecord->ordering) {
                                $instance->$key = $oldRecord->ordering;
                            } else {
                                $maxOrdering = $this->model::max('ordering') ?? 0;
                                $instance->$key = $maxOrdering + 1;
                            }
                        } else {
                            // Nếu tạo mới, lấy max ordering + 1
                            $maxOrdering = $this->model::max('ordering') ?? 0;
                            $instance->$key = $maxOrdering + 1;
                        }
                    } else {
                        $instance->$key = (int)$value;
                    }
                    break;
                default:
                    if (is_array($value)) {
                        $instance->$key = $value ? json_encode($value) : json_encode([]);
                    } else {
                        $instance->$key = $value;
                    }
                    break;
            }
        }

        $instance->published = @$this->data['published'] ? 1 : 0;
        $image = $request->file('image');
        if ($image) {
            // Gọi hàm upload từ trait
            // $path = '/assets/uploads/images/' . $this->prefix;
            $path = '/img/upload/images/' . $this->prefix;
            $instance->image = $this->upload($image, null, $path);
        } else {
            if (isset($this->data['image'])) {
                $pathImage = $this->data['image'];
                if ($pathImage) {
                    $old_image = $this->model::find($this->data['id']);
                    if (isset($old_image->image) && $old_image->image !== $this->data['image']) {
                        $this->deleteDuplicateFiles($old_image->image, '/img/upload/images_resized/' . $this->prefix . '/' . date('Y'));
                        $instance->image =  $this->upload2($pathImage, $this->sizes_resize, '/img/upload/images_resized/' . $this->prefix . '/' . date('Y'));
                    } else {
                        $instance->image =  $this->upload2($pathImage, $this->sizes_resize, '/img/upload/images_resized/' . $this->prefix . '/' . date('Y'));
                    }
                }
            }
        }

        if (!empty($otherImages)) {
            foreach ($otherImages as $otherImageName) {
                $image = $request->file($otherImageName);
                if ($image) {
                    $path = '/img/upload/images/' . $this->prefix;
                    $instance->$otherImageName = $this->upload($image, null, $path);
                } else {
                    if (isset($this->data[$otherImageName])) {
                        $pathImage = $this->data[$otherImageName];
                        if ($pathImage) {
                            $old_image = $this->model::find($this->data['id']);
                            if (isset($old_image->$otherImageName) && $old_image->$otherImageName !== $this->data[$otherImageName]) {
                                $this->deleteDuplicateFiles($old_image->$otherImageName, '/img/upload/images_resized/' . $this->prefix . '/' . date('Y'));
                                $instance->$otherImageName =  $this->upload2($pathImage, $this->sizes_resize, '/img/upload/images_resized/' . $this->prefix . '/' . date('Y'));
                            } else {
                                $instance->$otherImageName =  $this->upload2($pathImage, $this->sizes_resize, '/img/upload/images_resized/' . $this->prefix . '/' . date('Y'));
                            }
                        }
                    }
                }
            }
        }

        if (App::getLocale() !== 'vi') {
            $temp = [];
            foreach ($this->translations_field as $field) {
                if (request()->has($field)) {
                    $temp[$field] = request()->get($field);
                    unset($instance[$field]);
                }
            }
            $instance->save();
            $id = $instance->id;

            $this->save_translated($id, $temp);
        } else {
            $instance->save();
            $id = $instance->id;
        }


        return $id;
    }

    public function delete()
    {
        $request = request();
        $agreeDelete = $request->input('agreeDelete');
        $id = $request->input('id');
        $route = $request->input('route');

        if (!$agreeDelete) {
            return redirect()->route('dashboard')
                ->withErrors('Vui lòng chọn ít nhất một bản ghi để xóa');
        }

        $data = $this->model::findOrFail($id);
        if (@$data->image) {
            $imagePath = public_path('images/' . $data->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $data->delete();

        return json_encode(['success' => true, 'status' => 200, 'message' => 'Xóa bản ghi thành công', 'route' => route($route)]);

        // return redirect()->route($route)
        //     ->with('success', 'Xóa bản ghi thành công');
    }

    public function save_translated($id, $data)
    {
        $table_translations = $this->translations_table;
        $locale = App::getLocale();
        // dd(request()->all());
        foreach ($table_translations as $item) {
            if (Schema::hasTable($item)) {
                DB::table($item)->updateOrInsert(
                    ['record_id' => $id, 'locale' => $locale],
                    array_merge($data, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ])
                );
            }
        }
    }

    public function getAllProductCategoryIds($categoryIds)
    {
        $allIds = collect($categoryIds);
        $childIds = ProductCategoriesModel::whereIn('parent_id', $categoryIds)->where('published', 1)->pluck('id');

        if ($childIds->isNotEmpty()) {
            $allIds = $allIds->merge($this->getAllProductCategoryIds($childIds->toArray()));
        }

        return $allIds;
    }

    public function getParentProductCategories($categoryId)
    {
        $breadcrumbs = collect();

        $category = ProductCategoriesModel::where('id', $categoryId)->where('published', 1)->first();

        while ($category) {
            $breadcrumbs->prepend($category);
            $category = $category->parent;
        }

        return $breadcrumbs;
    }

    public function getAllCategoryIds($categoryIds)
    {
        $allIds = collect($categoryIds);
        $childIds = ProductCategoriesModel::whereIn('parent_id', $categoryIds)->pluck('id');

        if ($childIds->isNotEmpty()) {
            $allIds = $allIds->merge($this->getAllCategoryIds($childIds->toArray()));
        }

        return $allIds;
    }
}
