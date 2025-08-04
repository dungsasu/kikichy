<div>
    <x-related title="Thêm sản phẩm mua cùng" name="products_related"
        :data-component="@$data->products_related"
        :categories="$categories" 
        :route-ajax="route('get-products-by-category')" />
</div>

