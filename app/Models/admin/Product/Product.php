<?php

namespace App\Models\admin\Product;

use App\Models\admin\Filter\FilterProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Product\ProductSize as ProductSizeModel;  
use App\Models\admin\Product\ProductTranslate;
use App\Models\admin\Product\ProductCategories;
use Illuminate\Support\Facades\App;
use App\Models\admin\Campaign\Campaign as CampainModel;
use App\Models\admin\Promotion\DiscountCategory as DiscountCategoryModel;
use App\Traits\CommonFunctionTrait;
use App\Models\admin\News\News as NewsModel;
use App\Models\admin\Comment\ProductComment;
use App\Models\admin\Product\ProductColor as ProductColorModel;

class Product extends Model
{
    use CommonFunctionTrait;
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['href', 'quantity'];

    public function translation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->hasOne(ProductTranslate::class, 'record_id')->where('locale', $locale);
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }

    public function filters()
    {
        return $this->hasMany(FilterProduct::class, 'product_id', 'id');
    }

    public function versions_related()
    {
        return $this->belongsToMany(Product::class, 'products_related', 'product_id', 'related_product_id')
        ->wherePivot('type', 2);
    }

    public function products_related()
    {
        return $this->belongsToMany(Product::class, 'products_related', 'product_id', 'related_product_id')
        ->wherePivot('type', 1);
    }

    public function news()
    {
        return $this->belongsToMany(NewsModel::class, 'product_news_related', 'product_id', 'news_id', 'id', 'id');
    }

    public function delete()
    {
        // $this->productColorImages()->delete();
        foreach ($this->attributes as $attribute) {
            if (is_object($attribute) && isset($attribute->images)) {
                foreach ($attribute->images as $image) {
                    if (is_object($image)) {
                        $image->delete();
                    }
                }
                $attribute->delete();
            }
        }
                                       
        $this->versions_related()->detach();
        $this->products_related()->detach();
        $this->filters()->delete();
        $this->images()->delete();
        $this->news()->detach();

        return parent::delete();
    }

    public function sizes()
    {
        return $this->hasMany(ProductSizeModel::class, 'product_id', 'id');
    }

    public function colors()
    {
        return $this->hasMany(ProductColorModel::class, 'product_id', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(ProductCategories::class, 'category_id', 'id');
    }

    public function getSummaryAttribute()
    {
        return $this->getTranslatedAttribute('summary');
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }
    
    public function getGuideManagementAttribute()
    {
        return $this->getTranslatedAttribute('guide_management');
    }
    
    public function getReturnPolicyAttribute()
    {
        return $this->getTranslatedAttribute('return_policy');
    }
    
    protected function getTranslatedAttribute($attribute)
    {
        $locale = App::getLocale();

        if ($locale != 'vi' && $this->translation($locale)->exists()) {
            return $this->translation($locale)->where($this->attribute['id'])->first()->$attribute;
        }

        return $this->attributes[$attribute];
    } 

    public function getHrefAttribute()
    {
        return route('client.product', ['category' => @$this->category->alias, 'alias' => @$this->alias]);
    }

    public function getQuantityAttribute()
    {
        return $this->attributes()->sum('quantity');
    }

    public function getPriceFormatAttribute()
    {
        return $this->format_money($this->price);
    } 

    public function getPriceOldFormatAttribute()
    {
        return $this->format_money($this->price_old);
    } 

    function checkCategories($categories, $categories_wrapper)
    {
        $categoriesArray = explode(',', $categories);

        foreach ($categoriesArray as $category) {
            $pattern = '/(^|,)' . preg_quote($category, '/') . '(,|$)/';
            if (preg_match($pattern, $categories_wrapper)) {
                return true;
            }
        }
        return false;
    }
}
