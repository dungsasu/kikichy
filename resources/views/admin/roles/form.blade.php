@extends('admin.contentNavLayout')

@section('title', 'Chỉnh sửa role')

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection


@section('content')
    <div class="d-flex justify-content-between mb-4">
        <div></div>
        <div class="mt-4">
            <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
            <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
            <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
            <button type="reset" class="btn btn-outline-secondary"
                onclick="window.location.href = '{{ '/' . config('variables.admin') . '/roles' }}'">Đóng</button>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header">Chỉnh sửa vai trò</h4>
                <!-- Account -->
                <div class="card-body pt-2 mt-1">
                    <form action={{ route('admin.role.save') }} id="formAccountSettings" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ @$data->id }}">
                        <div class="row mt-2 gy-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{ @$data->name }}" />
                                    <label for="firstName">Tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="alias" id="alias"
                                        value="{{ @$data->alias }}" />
                                    <label for="lastName">Alias</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="ordering" name="ordering"
                                        value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}" placeholder="" />
                                    <label for="ordering">Thứ tự</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <label for="published">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$data->published || !@$data->id ? 'checked' : null }}>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>

            <div class="card">
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Tên Menu</th>
                            <th>Tên Submenu</th>
                            <th>URL Submenu</th>
                            <th>Xem (Chọn tất cả)</th>
                            <th>Sửa (Chọn tất cả)</th>
                            <th>Xoá (Chọn tất cả)</th>
                        </tr>
                        @foreach (@$menuData[0]->menu as $item)

                            @if (property_exists($item, 'name'))
                                @php
                                    $menu_name = $item->name;
                                    $menu_url = isset($item->url) ? $item->url : '';
                                @endphp

                                @if (property_exists($item, 'submenu'))
                                    @foreach ($item->submenu as $submenu)
                                        @php
                                            $submenu_name = $submenu->name;
                                            $submenu_url = isset($submenu->url) ? $submenu->url : '';
                                            $slug = isset($submenu->slug) ? $submenu->slug : '';

                                            $searchRoute = $slug . '.' . 'view';
                                            $searchRouteEdit = $slug . '.' . 'edit';
                                            $searchRouteDelete = $slug . '.' . 'delete';

                                            $view = false;
                                            $edit = false;
                                            $delete = false;

                                            if(@$data['permissions']) {
                                                foreach (@$data['permissions'] as $item) {
                                                if ($item['route'] === $searchRoute && $item['permission'] == 1) {
                                                    $view = true;
                                                }
                                                if ($item['route'] === $searchRouteEdit && $item['permission'] == 1) {
                                                    $edit = true;
                                                }
                                                if ($item['route'] === $searchRouteDelete && $item['permission'] == 1) {
                                                    $delete = true;
                                                }
                                            }
                                            }


                                        @endphp
                                        <input form="formAccountSettings" type="hidden" name="prefixes[]"
                                            value="{{ $slug }}">

                                        <tr>
                                            <td>{{ $menu_name }}</td>
                                            <td>{{ $submenu_name }}</td>
                                            <td>{{ $submenu_url }}</td>
                                            <td>
                                                <div class="form-check">
                                                    <input form="formAccountSettings" class="form-check-input"
                                                        name="{{ $slug }}_1" type="checkbox" value="1"
                                                        id="defaultCheck3-{{ $slug }}"
                                                        {{ $view ? 'checked' : null }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input form="formAccountSettings" class="form-check-input"
                                                        name="{{ $slug }}_2" type="checkbox" value="1"
                                                        {{ $edit ? 'checked' : null }} id="defaultCheck3">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input form="formAccountSettings" class="form-check-input"
                                                        name="{{ $slug }}_3" type="checkbox" value="1"
                                                        {{ $delete ? 'checked' : null }} id="defaultCheck3">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>{{ $menu_name }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('push_style')
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #007ec3;
            color: white;
            text-align: left;
            padding: 12px;
        }

        td {
            /* border: 1px solid #dddddd; */
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: rgba(212, 212, 212, 0.502)
        }
        th:hover {
            cursor: pointer;
        }
    </style>
@endpush

@push('push_script')
    <script>
        $(document).ready(function() {
            $('th').eq(3).on('click', function() {
                var checkboxes = $('td:nth-child(4) input[type=checkbox]');
                if (checkboxes.length == checkboxes.filter(':checked').length) {
                    checkboxes.prop('checked', false);
                } else {
                    checkboxes.prop('checked', true);
                }
            });
            $('th').eq(4).on('click', function() {
                var checkboxes = $('td:nth-child(5) input[type=checkbox]');
                if (checkboxes.length == checkboxes.filter(':checked').length) {
                    checkboxes.prop('checked', false);
                } else {
                    checkboxes.prop('checked', true);
                }
            });
            $('th').eq(5).on('click', function() {
                var checkboxes = $('td:nth-child(6) input[type=checkbox]');
                if (checkboxes.length == checkboxes.filter(':checked').length) {
                    checkboxes.prop('checked', false);
                } else {
                    checkboxes.prop('checked', true);
                }
            });
        });
    </script>
@endpush
