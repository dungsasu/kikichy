@extends('admin.contentNavLayout')

@php
    $title = 'Danh sách menu';
@endphp
@section('title', $title)

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex mb-3 justify-content-between" style="width: 100%;
    background: white;
    padding: 10px;
    position: sticky;
    top: 0;
    z-index: 10
    "
    >
    <select name="category" id="category" class="form-control item-menu select2 select" style="width: 20%">
        <option value="">Chọn danh mục menu</option>
        @foreach ($groups as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <button type="button" id="btnSave" class="btn btn-primary" disabled>
        <i class="fas fa-save"></i>
        <span class="ms-2">Lưu</span></button>

    </div>
    <div class="d-flex">
        <!-- Grid Card -->
        <div style="flex: 1" class="card border-primary mb-3 me-5">
            <div class="card-header bg-primary text-white">Thêm mới menu</div>
            <div class="card-body">
                <form id="frmEdit" class="form-horizontal">
                    <div class="form-group">
                        <label for="text">Tên</label>
                        <div class="input-group">
                            <input type="text" class="form-control item-menu" name="text" id="text"
                                placeholder="Tên menu">
                        </div>
                        <input type="hidden" name="icon" class="item-menu">
                    </div>
                    <x-choose-file title="Ảnh danh mục" id="image" field="image" type="Images" class="item-menu" />


                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="href">URL</label>
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light mb-3"
                                data-bs-toggle="modal" data-bs-target="#largeModal">
                                Tạo đường dẫn
                            </button>
                        </div>

                        <input type="text" class="form-control item-menu" id="href" name="href"
                            placeholder="URL">
                    </div>
                    <div class="form-group">
                        <label for="target">Trạng thái</label>
                        <select name="published" id="published" class="form-control item-menu">
                            <option value="0">--Chọn trạng thái--</option>
                            <option value="0">Ẩn</option>
                            <option value="1">Hiện</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="target">Nổi bật</label>
                        <select name="hot" id="hot" class="form-control item-menu">
                            <option value="0">--Chọn nổi bật--</option>
                            <option value="0">Không</option>
                            <option value="1">Có</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="target">In đậm</label>
                        <select name="bold" id="bold" class="form-control item-menu">
                            <option value="0">--Chọn in đậm--</option>
                            <option value="0">Không</option>
                            <option value="1">Có</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="target">Màu chữ</label>
                        <input type="text" class="form-control item-menu" id="color_code" name="color_code"
                            placeholder="Màu chữ">
                    </div>

                    <div class="form-group">
                        <label for="target">Target</label>
                        <select name="target" id="target" class="form-control item-menu">
                            <option value="_self">Self</option>
                            <option value="_blank">Blank</option>
                            <option value="_top">Top</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Tooltip</label>
                        <input type="text" name="title" class="form-control item-menu" id="title"
                            placeholder="Tooltip">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fas fa-sync-alt"></i>
                    Cập nhật</button>
                <button type="button" id="btnAdd" class="btn btn-success"><i class="fas fa-plus"></i> Thêm</button>
            </div>
        </div>
        <div style="flex: 1">
            <ul id="myEditor" class="sortableLists list-group">
            </ul>
        </div>
    </div>

    <div class="modal fade" id="largeModal" aria-modal="false" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel4">Tạo đường dẫn</h4>
                </div>
                <div class="modal-body create-link">
                    <div class="d-flex justify-content-between">
                        <div class="col-md-5">
                            {!! $treeLink !!}
                        </div>
                        <div class="col-md-6 p-3"
                            style="background-color: #f9f9f9; border: 1px solid #eee;
    border-radius: 5px;">
                            <div class="list-category mb-2">
                                <label for="">Danh mục</label>
                                <select style="width: 100%" name="link"
                                    class="form-select select2 form-select-sm select-create-link-category">
                                    <option value="0">--Chọn danh mục--</option>
                                </select>
                            </div>
                            <div class="list-item">
                                <label for="">Danh sách</label>
                                <select style="width: 100%" name="link"
                                    class="form-select select2 form-select-sm select-create-link">
                                    <option value="0">--Chọn danh sách--</option>
                                </select>
                            </div>
                            <div class="link-generate mt-3">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect"
                        data-bs-dismiss="modal">Đóng</button>
                    <button type="button"
                        class="create-link-btn-modal btn btn-primary waves-effect waves-light">Tạo</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('push_style')
    <style>
        .form-group {
            margin-bottom: 20px;
        }

        .create-link {
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

            .link-generate:empty {
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

@section('script_page');
    <script>
        let model, model_category, type, route, link_generate, csrfToken, category_id;

        const iconPickerOptions = {
            searchText: "Buscar...",
            labelHeader: "{0}/{1}"
        };

        const sortableListOptions = {
            placeholderCss: {
                'background-color': "#cccccc"
            }
        };

        const editor = new MenuEditor('myEditor', {
            listOptions: sortableListOptions,
            maxLevel: 3
        });
        editor.setForm($('#frmEdit'));
        editor.setUpdateButton($('#btnUpdate'));

        $(document).on('click', ".btnEdit", function() {
            console.log($(this).closest('[data]').data());
        });

        $("#btnUpdate").click(function() {
            editor.update();
            const arrayjson = editor.getString();
            $.post("{{ route('admin.menu.save') }}", {
                description: arrayjson,
                group_id: $("#category").val(),
                shouldRedirect: 0,
                published: 1,
                id: $(this).data("id"),
                _token: '{{ csrf_token() }}'
            }).done(response => {
                showToasts('Thành công', 'success');
                if (response.data) {
                    editor.setData(response.data.description);
                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log(textStatus, errorThrown);
            });
        });

        $("#image").change(function() {
            $(this).parent().prev().attr('src', $(this).val());
        });

        $('#btnAdd').click(function() {
            $("#btnSave").prop("disabled", false);
            editor.add();
        });

        $(document).on('click', '.btnRemove', function() {
            if ($("#myEditor").children().length === 0) {
                $("#btnSave").prop("disabled", true);
            }
        });

        $('#category').select2();

        $("#category").change(function() {
            $.post("{{ route('get-menu-items') }}", {
                group_id: $(this).val(),
                shouldRedirect: 0,
                published: 1,
                _token: '{{ csrf_token() }}'
            }).done(response => {
                if (response.data) {
                    editor.setData(JSON.parse(response.data.description));
                    $("#btnSave").prop("disabled", false);
                } else {
                    $("#myEditor").empty();
                    $("#btnSave").prop("disabled", true);
                }
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log(textStatus, errorThrown);
            });
        });

        $("#btnSave").click(function() {
            $("#btnUpdate").click();
        });

        function deleteItem() {
            const arrayjson = editor.getString();
            $.post("{{ route('admin.menu.save') }}", {
                description: arrayjson,
                group_id: $("#category").val(),
                shouldRedirect: 0,
                published: 1,
                id: $(this).data("id"),
                _token: '{{ csrf_token() }}'
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log(textStatus, errorThrown);
            });
        }

        $(".list-category, .list-item").hide();

        $(".create-link li").click(function() {
            $(".link-generate, .select-create-link-category, .select-create-link").empty();
            $(".create-link li").removeClass("active");
            $(this).addClass("active");

            csrfToken = $('meta[name="csrf-token"]').attr('content');
            $(".select-create-link-category").append('<option value="">--Chọn danh mục--</option>');
            $(".select-create-link").append('<option value="">--Chọn danh sách--</option>');

            model_category = $(this).data("model-category") || $(this).data("model");
            model = $(this).data("model");
            type = $(this).data("type");
            link = '{{ route('menu.list_category') }}';
            route = $(this).data('route');

            if (type === 'category') {
                $(".list-item").hide();
                $(".list-category").show();
            } else if (type === 'default') {
                $(".list-item").hide();
                $(".list-category").hide();
                $.get("{{ route('menu.create_link') }}", {
                    _token: csrfToken,
                    model: model,
                    type: type,
                    category_id: $(this).val(),
                    model_category: model_category,
                    route: route
                }).done(response => {
                    if (response.status === 200) {
                        $(".link-generate").addClass("alert alert-success").html('Thành công: <br> ' + response.data);
                        link_generate = response.data;
                    } else {
                        console.error('Unexpected response status: ' + response.status);
                    }
                }).fail((xhr, status, error) => {
                    console.error('AJAX Error: ' + status, error);
                });
                return;
            } else {
                $(".list-category, .list-item").show();
            }

            $.get(link, {
                _token: csrfToken,
                model: model,
                model_category: model_category,
                type: type
            }).done(response => {
                if (response.status === 200) {
                    response.data.forEach(item => {
                        $(".select-create-link-category").append(
                            `<option value="${item.id}">${item.treename}</option>`);
                    });
                    $('.select-create-link-category').select2({
                        dropdownParent: $('#largeModal')
                    });
                } else {
                    console.error('Unexpected response status: ' + response.status);
                }
            }).fail((xhr, status, error) => {
                console.error('AJAX Error: ' + status, error);
            });
        });

        $(".select-create-link-category").change(function() {
            $(".select-create-link, .link-generate").empty();
            $(".select-create-link").append('<option value="">--Chọn danh sách--</option>');
            category_id = $(this).val()
            const ajaxData = {
                _token: csrfToken,
                model: model,
                category_id,
                model_category,
                route,
                type
            };

            const ajaxOptions = {
                url: (type === 'category') ? "{{ route('menu.create_link') }}" :
                    '{{ route('menu.list_item') }}',
                method: 'GET',
                data: ajaxData
            };

            $.ajax(ajaxOptions).done(response => {
                if (response.status === 200) {
                    if (type === 'category') {
                        $(".link-generate").addClass("alert alert-success").html('Thành công: <br> ' + response.data);
                        link_generate = response.data;
                    } else {
                        response.data.forEach(item => {
                            $(".select-create-link").append(
                                `<option value="${item.id}">${item.name}</option>`);
                        });
                        $('.select-create-link').select2({
                            dropdownParent: $('#largeModal')
                        });
                    }
                } else {
                    console.error('Unexpected response status: ' + response.status);
                }
            }).fail((xhr, status, error) => {
                console.error('AJAX Error: ' + status, error);
            });
        });

        $(".select-create-link").change(function() {
            $(".link-generate").empty();
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
                    $(".link-generate").addClass("alert alert-success").html('Thành công: <br>' + response.data);
                    link_generate = response.data;
                } else {
                    console.error('Unexpected response status: ' + response.status);
                }
            }).fail((xhr, status, error) => {
                console.error('AJAX Error: ' + status, error);
            });
        });

        $(document).on('click', ".create-link-btn-modal", function() {
            $("#href").val(link_generate);
            $("#largeModal").modal('hide');
        });
    </script>
@endsection
