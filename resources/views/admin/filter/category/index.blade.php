@extends('admin.contentNavLayout')

@section('title', 'Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@php
    $type = [
        'string' => 'Chuỗi ký tự',
        'textarea' => 'Textarea',
        'single' => 'Chọn một',
        'multiple' => 'Chọn nhiều'
    ];
@endphp

@section('content')
    <div class="row gy-4">
        @if ($show_button_action)
            <x-button-action show="create,active,unactive,delete" :view="$view" :model="$model" />
        @endif 
        <div class="col-12">
            <x-filter prefix="{{ $prefix }}" :filter-value="$filter" >
                <div class="col-md-3 ms-3">
                    <select name="{{ $prefix ?? '' }}_group_id_filter" aria-controls="DataTables_Table_0" class="form-select select2 form-select-sm">
                        <option value="0">Lọc theo danh mục</option>
                        @foreach (@$groups as $group)
                            <option
                                {{ isset($filter[$prefix . '_group_id_filter']) && $group->id == $filter[$prefix . '_group_id_filter'] ? 'selected' : null }}
                                value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </x-filter>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <span class="form-check mb-0">
                                        <input id="selectAllRecord" class="form-record form-check-input me-1"
                                            type="checkbox" value="">
                                    </span>
                                </th>
                                <th class="text-truncate">Tên</th>
                                <th class="text-truncate">Nhóm</th>
                                <th class="text-truncate">Kiểu nhập</th>
                                <th class="text-truncate">Ngày tạo</th>
                                <th class="text-truncate">Trạng thái</th>
                                <th class="text-truncate">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($list->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endif
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}" />
                                    </td> 
                                    <td> 
                                        <h6 class="mb-0 text-truncate">{{ @$item->name }}</h6> 
                                    </td>
                                    <td>
                                        {{ @$item->group->name }}
                                    </td>
                                    <td>
                                        {{ @$type[$item->type] }}
                                    </td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime(@$item->created_at)) }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input data-model="{{ $model }}" data-id="{{ $item->id }}"
                                                data-link="{{ $item->published == 1 ? '/api/active' : '/api/unactive' }}"
                                                id="published" class="form-check-input form-check-input-status float-start"
                                                type="checkbox" value="1" name="published" role="switch"
                                                {{ $item->published == 1 ? 'checked' : null }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route($view . '.edit', ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route($view . '.delete', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.banner.categories.index']) }}">
                                                <i class="mdi mdi-trash-can-outline me-1"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $list->links() }}
        </div>
        <!--/ Data Tables -->
    </div>
@endsection
