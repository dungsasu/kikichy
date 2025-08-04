@extends('admin.contentNavLayout')

@section('title', 'Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')
    <div class="row gy-4">
        @if ($show_button_action)
            <div>
                <a href="{{ route('admin.user.create') }}" type="button"
                    class="btn rounded-pill btn-primary waves-effect waves-light">
                    <i class="mdi mdi-plus"></i>
                    Thêm
                </a>
            </div>
        @endif
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th class="text-truncate">Tên đăng nhập</th>
                                <th class="text-truncate">Email</th>
                                <th class="text-truncate">Vai trò</th>
                                <th class="text-truncate">Status</th>
                                <th class="text-truncate">Chức năng</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="{{ $item->image }}"
                                                    onerror="this.src='{{ asset('img/no-image.png') }}'" alt="Avatar"
                                                    class="rounded-circle">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-truncate">{{ @$item->username }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-truncate">{{ @$item->email }}</td>
                                    <td class="text-truncate">
                                        @if (strpos(@$item->roles->name, 'admin') !== false)
                                            <i class="mdi mdi-laptop mdi-24px text-danger me-1"></i>
                                        @endif
                                        {{ @$item->roles->name }}
                                    </td>
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
                                                href="{{ route('admin.user.edit', ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            @if (count($list) > 1)
                                                <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                    data-link="{{ route('admin.user.delete', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'user']) }}">
                                                    <i class="mdi mdi-trash-can-outline me-1"></i></a>
                                            @endif
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
