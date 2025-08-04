@extends('admin.contentNavLayout')

@section('title', 'Trình diễn thời trang')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection


@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')
    @php

    @endphp

    <div class="row gy-4">
        @if (@$show_button_action)
            <x-button-action show="create,duplicate,active,unactive,delete" create="{{ route($view . '.create') }}"
                :model="$model" />
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
                                <th class="text-truncate">Tên</th>
                                <th class="text-truncate">Trạng thái</th>
                                <th class="text-truncate">Ngày tạo</th>
                                <th class="text-truncate">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}" />
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <img style="width: 100px; height: 100px; object-fit: contain"
                                                    src="{{ $item->image }}"
                                                    onerror="this.src='{{ asset('img/no-image.png') }}'" alt="Avatar">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-truncate">{{ @$item->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->published == 0)
                                            <span class="badge bg-label-danger rounded-pill">Ngừng kích hoạt</span>
                                        @else
                                            <span class="badge bg-label-success rounded-pill">Kích hoạt</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate">{{ @$item->created_at }}

                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route($view . '.edit', ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route($view . '.delete', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.fashion.index']) }}">
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
