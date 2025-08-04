<div>
    <div class="text-danger fs-6 fw-medium mb-3">
        Lưu ý thêm sản phẩm, khi lưu có thể bị loại bỏ do nằm trong chương trình khác trùng thời gian KM.
    </div>
    <x-related title="Thêm sản phẩm Khuyến mại" name="products" 
        :data-component="@$data->products"
        :categories="$categories" 
        :route-ajax="route('get-products-by-category')"
        :dataTable="[
            'price_old_format' => [
                'title' => 'Giá góc',
            ],
            'price' => [
                'title' => 'Giá ưu đãi hoặc',
                'type' => 'text',
            ],
            'percent' => [
                'title' => 'Giảm %',
                'type' => 'text',
            ],
            'sold' => [
                'title' => 'Đã bán',
                'type' => 'text',
                'readonly' => true,
            ],
        ]"
    />
</div>