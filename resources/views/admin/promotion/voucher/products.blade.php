<div>
    <div class="text-danger fs-6 fw-medium mb-3">
         
    </div>
    <x-related title="Thêm các sản phẩm được áp dụng voucher" name="products" 
        :data-component="@$data->products"
        :categories="$categories" 
        :route-ajax="route('get-products-by-category')"
        :dataTable="[
            'price_old_format' => [
                'title' => 'Giá góc',
            ],
            'quantity' => [
                'title' => 'Tồn kho',
            ],
        ]"
    />
</div>