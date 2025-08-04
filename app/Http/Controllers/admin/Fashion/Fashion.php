<?php

namespace App\Http\Controllers\admin\Fashion;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\admin\Fashion\Fashion as FashionModel;
use Illuminate\Support\Facades\DB;

class Fashion extends BaseController
{
    public function __construct()
    {
        $view = 'admin.fashion';
        $prefix = 'fashion';
        parent::__construct(FashionModel::class, $view, $prefix);
    }

    public function save(Request $request) {
        
        $data = $request->all();
        unset($data['_token']);
        unset($data['fashion']);
        unset($data['ordering-fashion']);
        unset($data['type-fashion']);
        unset($data['link-fashion']);

        
        parent::setData($data);


        return parent::save($request);
    }


    function save_extend($id)
    {
        $this->save_fashion_image($id);
    }

    function save_fashion_image($id)
    {
        $fashion_image = request()->input('fashion');
        DB::table("fashion_images")->where('fashion_id', $id)->delete();

        if (isset($fashion_image) && count($fashion_image) > 0) {
            $types = request()->input('type-fashion');
            $orderings = request()->input('ordering-fashion');
            $links = request()->input('link-fashion');

            $fashion = DB::table("fashion_images");

            foreach ($fashion_image as $key => $item) {
                // Insert data and get the ID
                $rs = $fashion->insertGetId([
                    'image' => $item,
                    'type' => $types[$key] ?? null,
                    'fashion_id' => $id,
                    'ordering' => $orderings[$key] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'href' => $links[$key] ?? null,
                ]);
            
                if (!$rs) {
                    return false; // Exit loop if insertion fails
                }
            
                $pathImage = $item;
                if ($pathImage) {
                    $instance = $fashion->where('id', $rs)->first();

                    if ($instance) {
                        if (isset($instance->image) && $instance->image !== $item) {
                            $this->deleteDuplicateFiles($instance->image, '/assets/uploads/images/' . $this->prefix . '/' . date('Y'));
                        }
                        $updatedUrl = $this->upload2($pathImage, $this->sizes_resize, '/assets/uploads/images/' . $this->prefix . '/' . date('Y'));

                        $fashion->where('id', $rs)->update(['image' => $updatedUrl]);
                    }
                }
            }
        }
        return true;
    }
}
