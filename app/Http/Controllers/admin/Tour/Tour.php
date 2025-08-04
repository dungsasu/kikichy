<?php

namespace App\Http\Controllers\admin\Tour;

use Illuminate\Http\Request;
use App\Models\admin\Tour\Tour as TourModel;
use App\Http\Controllers\BaseController;
use App\Models\admin\Tour\TourCategories as Category;
use App\Models\admin\Tour\TourAttribute;
use App\Models\admin\Tour\TourAttributeImage;
use App\Models\admin\Tour\TourImage as TourImageModel;
use App\Traits\Tree;
use App\Models\admin\Tour\TourRelated;
use App\Services\Fast\FastService;
use App\Models\admin\Filter\FilterTable as FilterTableModel;
use App\Models\admin\Filter\Filter as FilterModel;
use App\Models\admin\Filter\FilterTour as FilterTourModel;
use App\Models\admin\News\NewsTourRelated;
use App\Models\admin\Tour\TourNewsRelated;
use App\Models\admin\News\NewsCategories;
use App\Services\TourService;

class Tour extends BaseController
{
    use Tree;

    public $fastService;
    public $getflyService;
    public $tourService;

    public function __construct(TourService $tourService)
    {
        parent::__construct();
        $this->model = TourModel::class;
        $this->controller = self::class;
        $this->view = 'admin.tour';
        $this->prefix = 'tour';

        $this->with = 'category';
        $this->searchField = 'name,code';

        $this->translations_field = [
            'summary',
            'description',
            'guide_management',
            'return_policy',
        ];
        // $this->translations_table = ['tours_translation'];


        $this->sizes_resize = [
            'resize' => [800, 800],
            'thumb' => [688, 387],
        ];

        $this->tourService = $tourService;
    }

    public function applyFilters(&$query, $filter)
    {
        parent::applyFilters($query, $filter);

        if (!empty($filter[$this->prefix . '_keyword_filter'])) {
            $query->orWhereHas('attributes', function ($query2) use ($filter) {
                $query2->where('code', 'like', '%' . $filter[$this->prefix . '_keyword_filter'] . '%');
            });
        }
        
    }

    public function index()
    {
        $filter = request()->session()->get('filter');
        $categories = Category::where('published', 1)->orderBy('ordering', 'desc')->get();
        $list_tree = $this->indentRows2($categories);


        parent::setData([
            'filter' => $filter,
            'categories' => $list_tree,
        ]);
        return parent::index();
    }

    public function create()
    {
        $categories = Category::where('published', 1)->orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($categories);

        $newsCategories = NewsCategories::where('published', 1)->orderBy('ordering', 'asc')->get();
        $newsCategoriesTree = $this->indentRows2($newsCategories);

        parent::setData([
            'categories' => $list_tree,
            'newsCategories' => $newsCategoriesTree,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $categories = Category::where('published', 1)->orderBy('ordering', 'asc')->get();
        $list_tree = $this->indentRows2($categories);

        $newsCategories = NewsCategories::where('published', 1)->orderBy('ordering', 'asc')->get();
        $newsCategoriesTree = $this->indentRows2($newsCategories);

        $data = TourModel::where('id', $id)
            ->with([
                'attributes' => function ($query) {
                    $query->orderBy('ordering', 'asc')->with([
                        'images' => function ($query) {
                            $query->orderBy('ordering', 'asc');
                        }
                    ]);
                },
                'images' => function ($query) {
                    $query->orderBy('ordering', 'asc');
                }
            ])
            ->first();

        $filterTable = FilterTableModel::where('id', @$data->category->filter_table_id)
            ->with(
                [
                    'items' => function ($query) {
                        $query->with(['category' => function ($query2) {
                            $query2->with('group');
                        }]);
                    }
                ]
            )
            ->first();
        if ($filterTable) {
            $filterCategory_id = $filterTable->items->pluck('filter_category_id');
            $filterSelectAvailable = FilterModel::whereIn('filter_category_id', $filterCategory_id)->get();
            $filters = $filterTable->items;
            $groupFilter = $filters->groupBy('category.group.name');

            $filterValues = [];
            foreach ($data->filters as $filter) {
                $filterValues[$filter->filter_category_id] = $filter->value;
            }

            foreach ($groupFilter as $groupName => $group) {
                foreach ($group as $itemGroup) {
                    if (isset($filterValues[$itemGroup->filter_category_id])) {
                        $itemGroup->value = $filterValues[$itemGroup->filter_category_id];
                    } else {
                        $itemGroup->value = null;
                    }
                }
            }
        }

        $attributes = @$data->attributes;

        parent::setData([
            'data' => $data,
            'attributes' => $attributes,
            'categories' => $list_tree,
            'groupFilter' => @$groupFilter,
            'filterSelectAvailable' => @$filterSelectAvailable,
            'newsCategories' => $newsCategoriesTree,
        ]);

        return parent::edit($id);
    }

    // protected function getRedirect()
    // {
    //     return 'edit_tours';
    // }

    public function save(Request $request)
    {
        $rules = $this->setRules();
        $customMessages = $this->setCustomMessages();
        $request->validate($rules, $customMessages);

        $data = $request->all();
        unset($data['_token']);
        unset($data['gallery']);
        unset($data['ordering-gallery']);
        unset($data['type-gallery']);
        unset($data['size']);
        unset($data['color']);
        unset($data['selectedProductIds']);

        $colors = request()->input('color');
        if (!is_array($colors)) {
            $colors = [];
        }

        foreach ($colors as $color) {
            unset($data['gallery_color' . $color]);
            unset($data['ordering-gallery_color' . $color]);
            unset($data['type-gallery_color' . $color]);
            unset($data['tour_color_ordering' . $color]);
        }
        if (!isset($data['info']['hot'])) {
            $data['info']['hot'] = 0;
        }
        if (!isset($data['info']['new'])) {
            $data['info']['new'] = 0;
        }
        if (!isset($data['inventory'])) {
            $data['inventory'] = 0;
        }
        if (!isset($data['info']['gmc'])) {
            $data['info']['gmc'] = 0;
        }
        if ($data['info']['category_id'] == 0) {
            return back()->withErrors([
                'Bạn chưa chọn danh mục',
            ]);
        } else {
            $data_category = Category::where('id', $data['info']['category_id'])->first();

            if ($data_category->list_parent_id) {
                $data['info']['category_id_wrapper'] = $data_category->list_parent_id . $data['info']['category_id'] . ',';
            } else {
                $data['info']['category_id_wrapper'] = ',' . $data['info']['category_id'] . ',';
            }
        }

        $data['info']['other_images'] = [
            'image_feature',
            'image_box'
        ];

        parent::setData($data);

        return parent::save($request);
    }

    function save_extend($id)
    {
        $this->saveAttribute($id);
        $this->saveVersionRelated($id);
        $this->saveToursRelated($id);
        $this->saveFilter($id);
        $this->saveImages($id);
        $this->saveNewsRelated($id);
    }

    private function saveNewsRelated($id)
    {
        $news = request()->input('news_related');

        $remove = explode(',', @$news['remove']);
        if (!empty($remove)) {
            foreach ($remove as $item) {
                NewsTourRelated::where('news_id', $item)->where('tour_id', $id)->delete();
                TourNewsRelated::where('tour_id', $id)->where('news_id', $item)->delete();
            }
        }

        unset($news['remove']);

        if (!empty($news['id'])) {
            $news = $news['id'];
            if (!empty($news)) {
                foreach ($news as $item) {
                    TourNewsRelated::updateOrCreate(
                        ['tour_id' => $id, 'news_id' => $item],
                    );
                    NewsTourRelated::updateOrCreate(
                        ['news_id' => $item, 'tour_id' => $id],
                    );
                }
            }
        }
    }

    private function saveImages($id)
    {
        $images = request()->input('images');
        if (!empty($images)) {
            $data = [];
            foreach ($images as $name => $nameValue) {
                foreach ($nameValue[0] as $key => $value) {
                    $data[$key][$name] = $value;
                }
            }

            $tour = TourModel::find($id);
            $tour->images()->delete();
            $tour->images()->createMany($data);
        }
    }

    private function saveVersionRelated($id)
    {
        $versions = request()->input('versions_related');

        $remove = explode(',', @$versions['remove']);
        if (!empty($remove)) {
            foreach ($remove as $item) {
                TourRelated::where('tour_id', $id)->where('related_tour_id', $item)->where('type', 2)->delete();
                TourRelated::where('tour_id', $item)->where('related_tour_id', $id)->where('type', 2)->delete();
            }
        }

        unset($versions['remove']);

        if (!empty($versions['id'])) {
            $versions = $versions['id'];

            if (!empty($versions)) {
                foreach ($versions as $item) {
                    TourRelated::updateOrCreate(
                        ['tour_id' => $id, 'related_tour_id' => $item, 'type' => 2],
                        ['type' => 2]
                    );
                    TourRelated::updateOrCreate(
                        ['tour_id' => $item, 'related_tour_id' => $id, 'type' => 2],
                        ['type' => 2]
                    );
                }
            }
        }
    }

    private function saveToursRelated($id)
    {
        $tours = request()->input('tours_related');

        $remove = explode(',', @$tours['remove']);
        if (!empty($remove)) {
            foreach ($remove as $item) {
                TourRelated::where('tour_id', $id)->where('related_tour_id', $item)->where('type', 1)->delete();
                TourRelated::where('tour_id', $item)->where('related_tour_id', $id)->where('type', 1)->delete();
            }
        }

        unset($tours['remove']);

        if (!empty($tours['id'])) {
            $tours = $tours['id'];
            if (!empty($tours)) {
                foreach ($tours as $item) {
                    TourRelated::updateOrCreate(
                        ['tour_id' => $id, 'related_tour_id' => $item, 'type' => 1],
                        ['type' => 1]
                    );
                    TourRelated::updateOrCreate(
                        ['tour_id' => $item, 'related_tour_id' => $id, 'type' => 1],
                        ['type' => 1]
                    );
                }
            }
        }
    }

    private function saveFilter($id)
    {
        $request = request()->all();

        if (!empty($request['filter'])) {
            foreach ($request['filter'] as $filter_category_id => $item) {
                $value = $item;
                if (is_array($item)) {
                    $value = ',' . implode(',', $item) . ',';
                }

                FilterTourModel::updateOrCreate(
                    ['tour_id' => $id, 'filter_category_id' => $filter_category_id],
                    ['value' => $value]
                );
            }
        }
    }

    function setRules()
    {
        return [
            'info.name' => 'required|string',
            'info.category_id' => 'required|integer',
        ];
    }

    function setCustomMessages()
    {
        return [
            'info.name.required' => 'Tên sản phẩm không được để trống',
            'info.category_id.required' => 'Danh mục không được để trống',
        ];
    }

    private function saveAttribute($id)
    {
        $attributes = request()->input('attributes');
        if (!empty($attributes)) {
            $data = [];

            $remove = explode(',', @$attributes['remove']);
            if (!empty($remove)) {
                TourAttribute::whereIn('id', $remove)->delete();
                TourAttributeImage::whereIn('tour_attribute_id', $remove)->delete();
            }

            unset($attributes['remove']);

            foreach ($attributes as $name => $nameValue) {
                foreach ($nameValue as $key => $value) {
                    if (in_array($name, ['image', 'ordering', 'type'])) {

                        foreach ($value as $k => $v) {
                            $data[$key]['images'][$k][$name] = $v;
                        }
                    } else {
                        $data[$key][$name] = $value;
                    }
                }
            }

            foreach ($data as $item) {
                $updateOrCreate = $item;
                $updateOrCreate['tour_id'] = $id;

                $updateOrCreate['price'] = $this->remove_fomart_money($updateOrCreate['price']);
                $updateOrCreate['price_old'] = $this->remove_fomart_money($updateOrCreate['price_old']);

                unset($updateOrCreate['images']);

                if (@$item['id']) {
                    $tourAttribute = TourAttribute::find($item['id']);
                    $tourAttribute->update($updateOrCreate);
                    $tourAttribute->images()->delete();
                } else {
                    $tourAttribute = TourAttribute::create($updateOrCreate);
                }

                if (!empty($item['images'])) {
                    $tourAttribute->images()->createMany($item['images']);
                }
            }
        }
    }

    public function updateInventory($code)
    {
        $fastService = new FastService();
        $response = $fastService->getItemInventory($code);

        if ($response->getData()->success) {
            return redirect()->back()->with('success', 'Cập nhật tồn kho thành công');
        } else {
            return redirect()->back()->with('error', 'Cập nhật tồn kho thất bại. Vui lòng thử lại sau');
        }
    }

    public function duplicate_extend($oldId, $newId)
    {
        $this->duplicateAttribute($oldId, $newId);
        $this->duplicateVersionRelated($oldId, $newId);
        $this->duplicateToursRelated($oldId, $newId);
        $this->duplicateFilter($oldId, $newId);
        $this->duplicateImages($oldId, $newId);
        $this->duplicateNewsRelated($oldId, $newId);
    }

    private function duplicateAttribute($oldId, $newId)
    {
        $attributes = TourAttribute::where('tour_id', $oldId)->get();
        foreach ($attributes as $attribute) {
            $newAttribute = $attribute->replicate();
            $newAttribute->tour_id = $newId;
            $newAttribute->save();

            foreach ($attribute->images as $image) {
                $newImage = $image->replicate();
                $newImage->tour_attribute_id = $newAttribute->id;
                $newImage->save();
            }
        }
    }

    private function duplicateVersionRelated($oldId, $newId)
    {
        $versions = TourRelated::where('tour_id', $oldId)->where('type', 2)->get();
        foreach ($versions as $version) {
            $newVersion = $version->replicate();
            $newVersion->tour_id = $newId;
            $newVersion->save();
        }
    }

    private function duplicateToursRelated($oldId, $newId)
    {
        $tours = TourRelated::where('tour_id', $oldId)->where('type', 1)->get();
        foreach ($tours as $tour) {
            $newTour = $tour->replicate();
            $newTour->tour_id = $newId;
            $newTour->save();
        }
    }

    private function duplicateFilter($oldId, $newId)
    {
        $filters = FilterTourModel::where('tour_id', $oldId)->get();
        foreach ($filters as $filter) {
            $newFilter = $filter->replicate();
            $newFilter->tour_id = $newId;
            $newFilter->save();
        }
    }

    private function duplicateImages($oldId, $newId)
    {
        $images = TourImageModel::where('tour_id', $oldId)->get();
        foreach ($images as $image) {
            $newImage = $image->replicate();
            $newImage->tour_id = $newId;
            $newImage->save();
        }
    }

    private function duplicateNewsRelated($oldId, $newId)
    {
        $news = TourNewsRelated::where('tour_id', $oldId)->get();
        foreach ($news as $item) {
            $newItem = $item->replicate();
            $newItem->tour_id = $newId;
            $newItem->save();
        }
    }

    // public function updatePrice($id)
    // {
    //     $updatePrice = $this->tourService->updatePrice($id);

    //     list($success, $errors) = array_values($updatePrice);

    //     return redirect()->back()->with('success', implode("<br />", $success))->withErrors($errors);
    // }

    // public function updateQuantity($id)
    // {
    //     $updateQuantity = $this->tourService->updateQuantity($id);

    //     list($success, $errors) = array_values($updateQuantity);

    //     return redirect()->back()->with('success', implode("<br />", $success))->withErrors($errors);
    // }

    // public function updateAllQuantity()
    // {
    //     return $this->tourService->updateAllQuantity();
    // }
}

