<?php

namespace App\Models\admin\Tour;

use App\Models\admin\Filter\FilterTour;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Tour\TourSize as TourSizeModel;  
use App\Models\admin\Tour\TourTranslate;
use App\Models\admin\Tour\TourCategories;
use Illuminate\Support\Facades\App;
use App\Models\admin\Campaign\Campaign as CampainModel;
use App\Models\admin\Promotion\DiscountCategory as DiscountCategoryModel;
use App\Traits\CommonFunctionTrait;
use App\Models\admin\News\News as NewsModel;
use App\Models\admin\Comment\TourComment;
use App\Models\admin\Tour\TourColor as TourColorModel;

class Tour extends Model
{
    use CommonFunctionTrait;
    use HasFactory;
    protected $table = 'tour';
    protected $guarded = ['id'];
    protected $appends = ['href', 'quantity'];

    public function translation($locale = null)
    {
        $locale = $locale ?: App::getLocale();
        return $this->hasOne(TourTranslate::class, 'record_id')->where('locale', $locale);
    }

    public function comments()
    {
        return $this->hasMany(TourComment::class, 'tour_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(TourImage::class, 'tour_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(TourAttribute::class, 'tour_id', 'id');
    }

    public function filters()
    {
        return $this->hasMany(FilterTour::class, 'tour_id', 'id');
    }

    public function versions_related()
    {
        return $this->belongsToMany(Tour::class, 'tours_related', 'tour_id', 'related_tour_id')
        ->wherePivot('type', 2);
    }

    public function tours_related()
    {
        return $this->belongsToMany(Tour::class, 'tours_related', 'tour_id', 'related_tour_id')
        ->wherePivot('type', 1);
    }

    // public function news()
    // {
    //     return $this->belongsToMany(NewsModel::class, 'tour_news_related', 'tour_id', 'news_id', 'id', 'id');
    // }

    public function delete()
    {
        // $this->tourColorImages()->delete();
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
        $this->tours_related()->detach();
        $this->filters()->delete();
        $this->images()->delete();
        $this->news()->detach();

        return parent::delete();
    }

    public function sizes()
    {
        return $this->hasMany(TourSizeModel::class, 'tour_id', 'id');
    }

    public function colors()
    {
        return $this->hasMany(TourColorModel::class, 'tour_id', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(TourCategories::class, 'category_id', 'id');
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
        if (!$this->alias || !$this->category || !$this->category->alias) {
            return null;
        }
        return route('client.tour', ['category' => $this->category->alias, 'alias' => $this->alias]);
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
