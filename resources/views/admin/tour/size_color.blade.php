<div class="col-md-12">
    <div class="row">
        <div class="col-md-3">
            <label>Kích thước</label>
        </div>
        <div class="col-md-3">
            <div class="pb-3 ps-3 pe-3">
                <label class="list-group-item">
                    <span class="form-check mb-0">
                        <input id="selectAllSize" class="form-size form-check-input me-1" type="checkbox" value="">
                        Tất cả
                    </span>
                </label>
            </div>
            <input type="text" id="search-size" class="form-control mb-2" style="border: none"
                placeholder="Tìm kiếm kích thước..">
            <div class="list-group" style="max-height: 200px; overflow: auto">
                @foreach ($sizes as $size)
                    <label class="list-group-item size-item">
                        <span class="form-check mb-0">
                            <input class="form-size form-check-input me-1" form="formAccountSettings" type="checkbox"
                                name="size[]" value="{{ $size->id }}"
                                {{ @$data->sizes && @$data->sizes->contains('size_id', $size->id) ? 'checked' : '' }}>
                            <span>{{ $size->name }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            <div class="selected-size mt-3">
                @foreach ($sizes as $size)
                    @if (@$data->sizes && @$data->sizes->contains('size_id', $size->id))
                        <span>{{ $size->name }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <label>Màu sắc</label>
        </div>
        <div class="col-md-3 ">
            <div class="pb-3 ps-3 pe-3 col-md-6 row">
                <label class="list-group-item">
                    <span class="form-check mb-0">
                        <input id="selectAllColor" class="form-color form-check-input me-1" type="checkbox"
                            value="">
                        Tất cả
                    </span>
                </label>
            </div>
            <input type="text" id="search-color" class="form-control mb-2" style="border: none"
                placeholder="Tìm kiếm màu sắc..">
            <div class="list-group" style="max-height: 200px; overflow: auto">
                @foreach ($colors as $color)
                    <label class="list-group-item color-item">
                        <span class="form-check mb-0 d-flex align-items-center justify-content-between">
                            <div>
                                <input class="form-color form-check-input me-1" form="formAccountSettings"
                                    type="checkbox" name="color[]" value="{{ $color->id }}"
                                    {{ @$data->colors && @$data->colors->contains('color_id', $color->id) ? 'checked' : '' }}>
                                <span>{{ $color->name }}</span>
                            </div>
                            <div
                                style="background-color: {{ $color->code }}; width: 10px; height: 10px; border-radius: 10px; border: 1px solid #ccc">
                            </div>
                        </span>
                    </label>
                @endforeach
            </div>
            <div class="selected-color mt-3">
                @foreach ($colors as $color)
                    @if (@$data->colors && @$data->colors->contains('color_id', $color->id))
                        <span>{{ $color->name }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="row">
            <div class="col-md-3">
                <p>Ảnh màu sắc</p>
            </div>
            <div id="color-container" class="col-md-9">
                @if (isset($images_colors) && count($images_colors) > 0)
                    @foreach ($images_colors as $key => $images)
                        <div data-color-id="{{ $images['data']['color_id'] }}" class="color-display mb-3">
                            <div class="color-name">{{ $images['data']['name'] }}</div>
                            <div class="col-md-4 color-ordering">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" value="{{ @$images['data']['pivot']['ordering'] }}"
                                        type="text" id="ordering" form="formAccountSettings"
                                        name="product_color_ordering{{ $key }}" />
                                    <label for="ordering">Thứ tự</label>
                                </div>
                            </div>
                            <x-gallery name="gallery_color{{ $key }}" :data-component="$images['images']" field="image"
                                type="Images" />
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>

</div>

@push('push_style')
    <style>
        .color-display {
            position: relative;
        }

        .color-name {
            position: absolute;
            top: 20px;
            left: 20px;
            white-space: nowrap;
            color: #333
        }

        .color-ordering {
            position: absolute;
            top: 10px;
            left: 100px;
            white-space: nowrap;
            color: #333
        }
    </style>
@endpush
@push('push_script')
    <script>
        $(document).ready(function() {
            $('#selectAllSize').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.form-size[type=checkbox]').not('#selectAll').prop('checked', isChecked);
            });

            $('#selectAllColor').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.form-color[type=checkbox]').not('#selectAll').prop('checked', isChecked);
            });

            $('#search-color').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.color-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('#search-size').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.size-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            var selectedColors = [];
            var selectedSizes = [];

            $('.form-color, .form-size').on('change', function() {
                selectedColors = [];
                selectedSizes = [];

                $('.color-item .form-color:checked').each(function() {
                    var colorName = $(this).closest('.color-item').find('.form-check span').text();
                    selectedColors.push(colorName);
                });

                $('.size-item .form-size:checked').each(function() {
                    var sizeName = $(this).closest('.size-item').find('.form-check span').text();
                    selectedSizes.push(sizeName);
                });

                $(".selected-size").html(selectedSizes.join(', '));
                $(".selected-color").html(selectedColors.join(', '));

                updateColorDivs();
            });

            function updateColorDivs() {
                $('.form-color').each(function() {
                    var colorValue = $(this).val();
                    if ($(this).is(':checked')) {
                        if ($('#color-container').find(`div[data-color-id="${colorValue}"]`).length === 0) {
                            var colorName = $(this).closest('.form-check').find('span').text().trim();
                            $.ajax({
                                url: '/api/get-gallery-component/' + colorValue,
                                type: 'GET',
                                success: function(response) {
                                    var colorDiv = $(
                                        `<div data-color-id="${colorValue}" class="color-display mb-3"></div>`
                                    );
                                    var colorNameDiv = $('<div class="color-name"></div>').text(
                                        colorName);
                                    var colorBox = $(response.html);
                                    colorDiv.append(colorNameDiv);
                                    colorDiv.append(colorBox);
                                    console.log(response);
                                    $('#color-container').append(colorDiv);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching component:', error);
                                }
                            });
                        }
                    } else {
                        var colorDiv = $('#color-container').find(`div[data-color-id="${colorValue}"]`);
                        if (colorDiv.length > 0) {
                            colorDiv.remove();
                        }
                    }
                });
            }
        });
    </script>
@endpush
