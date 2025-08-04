<?php

namespace App\Http\Controllers\admin\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\admin\Gallery\GalleryCategories as GalleryCategoriesModel;

class GalleryCategories extends BaseController
{
    public function __construct()
    {
        $view = 'admin.gallery.categories';
        $prefix = 'gallery_categories';
        parent::__construct(GalleryCategoriesModel::class, $view, $prefix);
    }

    public function setRedirect()
    {
        return ['create_gallery-categories', 'edit_gallery-categories'];
    }
}
