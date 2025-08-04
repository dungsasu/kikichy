<div> 
    <x-related title="Thêm tin tức liên quan" name="news_related"
        :data-component="@$data->news"
        :categories="$newsCategories" 
        :route-ajax="route('get-news-by-category')" />
</div>

