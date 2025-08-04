@extends('admin.contentNavLayout')

@section('title', 'Danh mục sản phẩm')

@section('page-style')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/css/bootstrap-iconpicker.min.css">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection


@section('content')
    <div class="row gy-4">
        @if (@$show_button_action)
            <x-button-action show="create,duplicate,active,unactive,delete" create="{{ route('admin.tour.categories.create') }}" :model="$model" />
        @endif
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
                                <th class="text-truncate">Tên danh mục</th>
                                <th class="text-truncate">Sắp xếp</th>
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
                            @foreach ($categories as $item)
                                @php
                                    $item = (object) $item;
                                @endphp
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}" />
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-truncate">{!! @$item->treename !!}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ @$item->ordering }}</td>
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
                                            <a style="width: 30px;" class="dropdown-item p-1"
                                                href="{{ route('admin.tour.categories.edit', ['id' => @$item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px;" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route('admin.tour.categories.delete', ['id' => @$item->id, 'agreeDelete' => @$item->id, 'route' => 'admin.tour.categories.index']) }}">
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
        </div>
        <!--/ Data Tables -->
    </div>
@endsection
