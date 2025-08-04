<div class="form-group">
    <div class="d-flex justify-content-between">
        <label for="href">URL</label>
        <button type="button" class="btn btn-outline-primary waves-effect waves-light mb-3" data-bs-toggle="modal" data-bs-target="#{{ $name }}Modal">
            Tạo đường dẫn
        </button>
    </div>

    {{-- <input type="text" class="form-control item-menu" id="{{ $name }}" name="{{ $name }}" placeholder="URL"> --}}
    <div class="form-floating form-floating-outline">
        <input class="form-control item-menu" type="text" id="{{ $name }}" name="{{ $name }}" placeholder="URL"
            value="{{ @$dataComponent }}" />
        <label for="ordering">Url</label>
    </div>
</div>

<div class="modal fade" id="{{ $name }}Modal" aria-modal="false" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $name }}ModalLabel">Tạo đường dẫn</h4>
            </div>
            <div class="modal-body {{ $name }}-create-link">
                <div class="d-flex justify-content-between">
                    <div class="col-md-5">
                        {!! $treeLink !!}
                    </div>
                    <div class="col-md-6 p-3 rounded-2" style="background-color: #f9f9f9; border: 1px solid #eee;">
                        <div class="{{ $name }}-list-category mb-2">
                            <label for="">Danh mục</label>
                            <select style="width: 100%"  
                                class="form-select select2 form-select-sm {{ $name }}-select-create-link-category">
                                <option value="0">--Chọn danh mục--</option>
                            </select>
                        </div>
                        <div class="{{ $name }}-list-item">
                            <label for="">Danh sách</label>
                            <select style="width: 100%"  
                                class="form-select select2 form-select-sm {{ $name }}-select-create-link">
                                <option value="0">--Chọn danh sách--</option>
                            </select>
                        </div>
                        <div class="{{ $name }}-link-generate mt-3">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="{{$name}}-create-link-btn-modal btn btn-primary waves-effect waves-light">Tạo</button>
            </div>
        </div>
    </div>
</div>

@push('push_style')
    <style> 

        .{{ $name }}-create-link {
            ul {
                list-style-type: none;
                margin-left: 20px;
                padding-left: 0;

                span {
                    color: #333;
                    font-weight: bold;
                    margin-bottom: 10px;
                    display: block;
                }
            }

            .{{ $name }}-link-generate:empty {
                background-color: transparent !important;
                border: none !important;
                padding: 0 !important;
            }

            ul li {
                color: #333;
                line-height: 1.6;
                padding-left: 20px;
                border-radius: 5px;
                padding: 8px 10px;
                border: 1px solid #eee;

                &:hover {
                    color: #007ec3;
                    cursor: pointer;
                    background-color: #f9f9f9;
                    /* color: white; */
                }
            }
        }

        .select2-dropdown {
            z-index: 2000 !important;
        }

        .hidden-important {
            display: none !important;
        }
    </style>
@endpush

@push('pricing-script')
    <script>
        let csrfToken = $('meta[name="csrf-token"]').attr('content')

        let model, model_category, type, route, link_generate, category_id

        $(document).on('click', ".{{$name}}-create-link-btn-modal", function() {
            $("#{{ $name }}").val(link_generate)
            $("#{{ $name }}Modal").modal('hide')
        })

        $(".{{ $name }}-list-category, .{{ $name }}-list-item").hide();

        $(".{{ $name }}-create-link li").click(function() {
            $(".{{ $name }}-link-generate, .{{ $name }}-select-create-link-category, .{{ $name }}-select-create-link").empty()
            $(".{{ $name }}-create-link li").removeClass("active")
            $(this).addClass("active")

            $(".{{ $name }}-select-create-link-category").append('<option value="">--Chọn danh mục--</option>')
            $(".{{ $name }}-select-create-link").append('<option value="">--Chọn danh sách--</option>')

            model_category = $(this).data("model-category") || $(this).data("model")
            model = $(this).data("model")
            type = $(this).data("type")
            link = '{{ route('menu.list_category') }}'
            route = $(this).data('route')

            if (type === 'category') {
                $(".{{ $name }}-list-item").hide()
                $(".{{ $name }}-list-category").show()
            } else if (type === 'default') {
                $(".{{ $name }}-list-item").hide()
                $(".{{ $name }}-{{ $name }}-list-category").hide()
                $.get("{{ route('menu.create_link') }}", {
                    _token: csrfToken,
                    model: model,
                    type: type,
                    category_id: $(this).val(),
                    model_category: model_category,
                    route: route
                }).done(response => {
                    if (response.status === 200) {
                        $(".{{ $name }}-link-generate").addClass("alert alert-success").html('Thành công: <br> ' + response.data)
                        link_generate = response.data
                    } else {
                        console.error('Unexpected response status: ' + response.status)
                    }
                }).fail((xhr, status, error) => {
                    console.error('AJAX Error: ' + status, error)
                })
                return
            } else {
                $(".{{ $name }}-list-category, .{{ $name }}-list-item").show()
            }

            $.get(link, {
                _token: csrfToken,
                model: model,
                model_category: model_category,
                type: type
            }).done(response => {
                if (response.status === 200) {
                    response.data.forEach(item => {
                        $(".{{ $name }}-select-create-link-category").append(`<option value="${item.id}">${item.treename}</option>`)
                    })
                    $('.{{ $name }}-select-create-link-category').select2({
                        dropdownParent: $('#{{ $name }}Modal')
                    })
                } else {
                    console.error('Unexpected response status: ' + response.status)
                }
            }).fail((xhr, status, error) => {
                console.error('AJAX Error: ' + status, error)
            })
        })

        $(".{{ $name }}-select-create-link-category").change(function() {
            $(".{{ $name }}-select-create-link, .{{ $name }}-link-generate").empty()
            $(".{{ $name }}-select-create-link").append('<option value="">--Chọn danh sách--</option>')

            category_id = $(this).val()
            const ajaxData = {
                _token: csrfToken,
                model: model,
                category_id,
                model_category,
                route,
                type
            }

            const ajaxOptions = {
                url: (type === 'category') ? "{{ route('menu.create_link') }}" : '{{ route('menu.list_item') }}',
                method: 'GET',
                data: ajaxData
            }

            $.ajax(ajaxOptions).done(response => {
                if (response.status === 200) {
                    if (type === 'category') {
                        $(".{{ $name }}-link-generate").addClass("alert alert-success").html('Thành công: <br> ' + response.data)
                        link_generate = response.data;
                    } else {
                        response.data.forEach(item => {
                            $(".{{ $name }}-select-create-link").append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                        $('.{{ $name }}-select-create-link').select2({
                            dropdownParent: $('#{{ $name }}Modal')
                        })
                    }
                } else {
                    console.error('Unexpected response status: ' + response.status)
                }
            }).fail((xhr, status, error) => {
                console.error('AJAX Error: ' + status, error)
            })
        })

        $(".{{ $name }}-select-create-link").change(function() {
            $(".{{ $name }}-link-generate").empty()
            $.get("{{ route('menu.create_link') }}", {
                _token: csrfToken,
                model: model,
                id: $(this).val(),
                category_id,
                model_category: model_category,
                route: route,
                type
            }).done(response => {
                if (response.status === 200) {
                    $(".{{ $name }}-link-generate").addClass("alert alert-success").html('Thành công: <br>' + response.data)
                    link_generate = response.data
                } else {
                    console.error('Unexpected response status: ' + response.status)
                }
            }).fail((xhr, status, error) => {
                console.error('AJAX Error: ' + status, error)
            })
        })
    </script>
@endpush
