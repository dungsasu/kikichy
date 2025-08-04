@extends('admin.contentNavLayout')

@section('title', 'Chương trình khuyến mại')


@section('content')
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
                                        <input id="selectAllRecord" class="form-record form-check-input me-1" type="checkbox"
                                            value="">
                                    </span>
                                </th>
                                <th class="text-truncate">Tên chương trình</th>
                                <th class="text-truncate">Nền tảng áp dụng</th>
                                <th class="text-truncate">Trạng thái</th>
                                <th class="text-truncate">Ngày bắt đầu</th>
                                <th class="text-truncate">Ngày kết thúc</th>
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
                                            <div class="avatar avatar-sm me-3">
                                                <img src="{{ $item->image }}"
                                                    onerror="this.src='{{ asset('img/no-image.png') }}'" alt="Avatar"
                                                    class="rounded-circle">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-truncate">{{ @$item->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->applicable_platform == '1')
                                            <span class="badge bg-label-success me-2 ms-2 rounded-pill">WEB</span>
                                        @elseif($item->applicable_platform == '2')
                                            <span class="badge bg-label-warning me-2 ms-2 rounded-pill">APP</span>
                                        @elseif($item->applicable_platform == '3')
                                            <span class="badge bg-label-primary me-2 ms-2 rounded-pill">TẤT CẢ</span>
                                        @endif
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
                                    <td style="max-width: 50%;">{{ @$item->start_date }}</td>
                                    <td>{{ @$item->end_date }}</td>
                                    <td class="text-truncate">{{ @$item->end_date }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route($view . '.edit', ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route($view . '.delete', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => $view . '.index']) }}">
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
