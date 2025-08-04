<?php

namespace App\Http\Controllers\admin\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\admin\Gallery\Gallery as GalleryModel;
use App\Models\admin\Gallery\GalleryCategories as GalleryCategoryModel;
use App\Models\admin\Gallery\GalleryImages as GalleryImageModel;

class Gallery extends BaseController
{
    public function __construct()
    {
        $view = 'admin.gallery';
        $prefix = 'gallery';
        parent::__construct(GalleryModel::class, $view, $prefix);
    }

    public function index()
    {
        $filter = Request()->session()->get('filter');
        $categories = GalleryCategoryModel::where('published', 1)->orderBy('created_at', 'desc')->get();

        $list = GalleryModel::with('category')->paginate($this->limit);
        parent::setData([
            'filter' => $filter,
            'categories' => $categories,
            'list' => $list
        ]);
        return parent::index();
    }

    public function create()
    {
        $categories = GalleryCategoryModel::where('published', 1)->get();
        parent::setData([
            'categories' => $categories,
        ]);
        return parent::create();
    }

    public function edit($id)
    {
        $categories = GalleryCategoryModel::where('published', 1)->get();
        parent::setData([
            'categories' => $categories,
        ]);
        return parent::edit($id);
    }

    public function setRedirect()
    {
        return ['create_gallery', 'edit_gallery'];
    }

    public function save(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['gallery']);
        unset($data['ordering-gallery']);
        unset($data['type-gallery']);
        unset($data['id-gallery']);

        parent::setData([
            'id' => $request->id,
            'ordering' => $request->ordering,
            'name' => $request->name,
            'published' => $request->published,
            'category_id' => $request->category_id,
            'created_at' => date('Y-m-d H:i:s'),
            'shouldRedirect' => $request->shouldRedirect
        ]);


        return parent::save($request);
    }

    public function save_extend($id)
    {
        $this->save_extend_gallery(Request(), $id);
    }

    public function save_extend_gallery($request, $id)
    {
        $gallery = $request->input('gallery');
        // dd($url);
        GalleryImageModel::where('gallery_id', $id)->delete();
        if (isset($gallery) && count($gallery) > 0) {
            $types = $request->input('type-gallery');
            $orderings = $request->input('ordering-gallery');
            $href = $request->input('link-gallery');
            foreach ($gallery as $key => $item) {
                $rs = GalleryImageModel::create([
                    'gallery_id' => $id,
                    'type' => strtolower($types[$key]) ?? null,
                    'url' => $item,
                    'created_at' => date('Y-m-d H:i:s'),
                    'published' => 1,
                    'ordering' => $orderings[$key] ?? null,
                    'href' => $href[$key] ?? null
                ]);

                $pathImage = $item;
                if ($pathImage) {
                    $instance = GalleryImageModel::find($rs->id);

                    if (isset($instance->url) && $instance->url !== $item) {
                        $this->deleteDuplicateFiles($instance->url, '/assets/uploads/images/' . $this->prefix . '/' . date('Y'));
                        $instance->url = $this->upload2($pathImage, $this->sizes_resize, '/assets/uploads/images/' . $this->prefix . '/' . date('Y'));
                    } else {
                        $instance->url = $this->upload2($pathImage, $this->sizes_resize, '/assets/uploads/images/' . $this->prefix . '/' . date('Y'));
                    }
                    $instance->save();
                }

                if (!$rs) {
                    return false;
                }
            }
        }
        return true;
    }
}
