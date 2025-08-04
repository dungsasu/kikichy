<div class="row">
    <div class="col-md-4">
        <div class="form-floating form-floating-outline">
            <input class="form-control" type="text" name="keyword" id="keyword" value="" placeholder="" />
            <label for="lastName">Tìm kiếm sản phẩm...</label>
        </div>
    </div>
    <div class="col-md-4">
        @if (isset($categories) && count($categories) > 0)
            <select name="category_id" class="form-select select2 form-select-sm" style="width: 100%">
                <option value="0">---Danh mục---</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{!! $category->treename ? $category->treename : $category->name !!}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="row mt-3 align-items-center">
        <div class="col">
            <div class="product-related">
                <p> Danh sách sản phẩm</p>
                <div class="left">
                </div>
            </div>
        </div>
        <div style="width: 5%">
            <button class="btn btn-outline-primary col-md-1 mb-4" id="selectAll">>></button>
            <button class="btn btn-outline-primary col-md-1" id="deleteAll">
                << </button>
        </div>
        <div class="col">
            <div class="product-related">
                <p> Sản phẩm đã chọn</p>
                <div class="right">
                    @if (!empty($related) && count($related) > 0)
                        @foreach ($related as $item)
                            <div data-id="{{ $item->id }}" class="product-related-item-right">
                                <img onerror="this.src='/img/no-image.png'" src="{{ $item->image }}"
                                    alt="{{ $item->name }}">
                                <div class="ms-3">
                                    <h6>{{ $item->name }}</h6>
                                    <span>{{ $item->price }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="selectedProductIds" form="formAccountSettings" name="product_related"
        value="{{ @$related && implode(',', @$related->pluck('id')->toArray()) ? implode(',', @$related->pluck('id')->toArray()) : null }}">
</div>

@push('push_script')
    <script>
        $(document).ready(function() {
            $('#selectAll').click(function() {
                $('.product-related-item-left').removeClass('product-related-item-left').addClass(
                    'product-related-item-right').appendTo('.right');
            });

            $('#deleteAll').click(function() {
                $('.product-related-item-right').removeClass('product-related-item-right').addClass(
                    'product-related-item-left').appendTo('.left');
            });
            function debounce(func, wait) {
                let timeout;

                return function(...args) {
                    const context = this;

                    // Hủy timeout cũ nếu người dùng tiếp tục gõ
                    clearTimeout(timeout);

                    // Đặt timeout mới để gọi func sau khi chờ đợi `wait` ms
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            }
            $("#keyword").on("keyup", debounce(function() {
                var keyword = $(this).val().toLowerCase(); // Lấy giá trị từ ô input và chuyển về chữ thường

                if (keyword.length > 0) { // Kiểm tra nếu có từ khóa
                    $.ajax({
                        url: '/api/get-products-by-keyword', // Endpoint để tìm kiếm sản phẩm
                        type: 'GET',
                        data: {
                            keyword: keyword // Gửi từ khóa đến server
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('.left').html(''); // Xóa nội dung cũ
                            if (data.data.length > 0) {
                                var productsHtml = '';
                                var selectedProductIds = $('.product-related-item-right').map(
                                    function() {
                                        return $(this).data('id');
                                    }).get();

                                $.each(data.data, function(index, product) {
                                    if ($.inArray(product.id, selectedProductIds) === -
                                        1) {
                                        productsHtml += `
                            <div data-id="${product.id}" class="product-related-item-left">
                                <img onerror="this.src='/img/no-image.png'" src="${product.image}" alt="${product.name}">
                                <div class="ms-3">
                                    <h6>${product.name}</h6>
                                    <span>${product.price}</span>
                                </div>
                            </div>
                            `;
                                    }
                                });
                                $('.left').html(productsHtml); // Hiển thị danh sách sản phẩm
                            } else {
                                $('.left').html(
                                    '<p>Không tìm thấy sản phẩm phù hợp.</p>'
                                    ); // Thông báo nếu không tìm thấy sản phẩm
                            }
                        },
                        error: function() {
                            $('.left').html(
                                '<p>Đã xảy ra lỗi khi tìm kiếm sản phẩm.</p>'); // Xử lý lỗi
                        }
                    });
                } else {
                    $('.left').html(
                        '<p>Vui lòng nhập từ khóa để tìm kiếm sản phẩm.</p>'
                        ); // Xóa nội dung nếu không có từ khóa
                }
            }, 300));
            $('select[name="category_id"]').on('change', function() {
                var category_id = $(this).val();
                if (category_id) {
                    $.ajax({
                        url: '/api/get-products-by-category',
                        type: 'GET',
                        data: {
                            category_id: category_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('.left').html('');
                            if (data.data.length > 0) {
                                var productsHtml = '';
                                var selectedProductIds = $('.product-related-item-right').map(
                                    function() {
                                        return $(this).data('id');
                                    }).get();
                                $.each(data.data, function(index, product) {
                                    if ($.inArray(product.id, selectedProductIds) === -
                                        1) {
                                        productsHtml += `
                            <div data-id="${product.id}" class="product-related-item-left">
                                <img onerror="this.src='/img/no-image.png'" src="${product.image}" alt="${product.name}">
                                <div class="ms-3">
                                <h6 >${product.name}</h6>
                                <span>${product.price}</span>
                                </div>
                            </div>
                            `;
                                    }
                                });
                                $('.left').html(productsHtml);
                            } else {
                                $('.left').html('<p>Không tìm thấy sản phẩm phù hợp.</p>');
                            }
                        }
                    });
                }
            });
            $(document).on('click', '.product-related-item-left', function() {
                var productId = $(this).data('id'); // Get the product id from the clicked element

                // Check if an element with this product id already exists in .product-related-item-right
                if ($('.product-related-item-right[data-id="' + productId + '"]').length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Bạn đã chọn sản phẩm này rồi!',
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                } else {
                    // Move this item to the right
                    $(this).removeClass('product-related-item-left').addClass('product-related-item-right')
                        .appendTo('.right');
                }
            });
            $(document).on('click', '.product-related-item-right', function() {
                // Move this item back to the left
                $(this).removeClass('product-related-item-right').addClass('product-related-item-left')
                    .appendTo('.left');
            });
        });

        function updateSelectedProductIds() {
            setTimeout(function() {
                var selectedProductIds = $('.right .product-related-item-right').map(function() {
                    return $(this).data('id');
                }).get();
                console.log(selectedProductIds)
                $('#selectedProductIds').val(selectedProductIds.join(','));
            }, 0);
        }

        // Call updateSelectedProductIds whenever there is a change in the .right div
        $('#selectAll, #deleteAll').click(updateSelectedProductIds);
        $(document).on('click', '.product-related-item-left, .product-related-item-right', updateSelectedProductIds);
    </script>
@endpush

@push('push_style')
    <style>
        .product-related {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); */
            background-color: #fff;
            /* Add a background color */
        }

        .product-related {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); */
        }

        .product-related p {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .left,
        .right {
            height: 300px;
            overflow-y: auto;
            /* border: 1px solid #ccc; */
            /* padding: 10px; */
        }

        .form-select {
            margin-bottom: 20px;
        }

        .product-related-item-left,
        .product-related-item-right {
            border: 1px solid #ddd;
            padding: 8px;
            margin-top: 8px;
            /* box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); */
            transition: 0.3s;
            display: flex;
            /* Add this */
        }

        .product-related-item-left:hover,
        .product-related-item-right:hover {
            /* box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2); */
        }

        .product-related-item-left img,
        .product-related-item-right img {
            width: 50px;
            /* Adjust as needed */
            height: 50px;
            object-fit: cover;
            height: auto;
        }

        .product-related-item-left p,
        .product-related-item-right p {
            color: #777;
            font-size: 13px;
        }
    </style>
@endpush
