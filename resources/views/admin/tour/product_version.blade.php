<div>
    <x-related title="Thêm sản phẩm cùng phân khúc" name="versions_related" 
        :data-component="@$data->versions_related"
        :categories="$categories" 
        :route-ajax="route('get-products-by-category')" />
</div>
 
