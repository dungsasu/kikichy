@extends('admin.contentNavLayout')

@section('title', 'Màu sắc sản phẩm')


@section('content')
    <div class="row gy-4">
        @if ($show_button_action)
            <x-button-action show="create,duplicate,active,unactive,delete" create="{{ route('create_color') }}"
                :model="$model" />
        @endif
        <x-filter prefix="{{ $prefix }}" :categories="[]" :filter-value="$filter" field="image" />

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
                                <th class="text-truncate">Tên màu sắc</th>
                                <th class="text-truncate">Mã màu</th>
                                <th class="text-truncate">Sắp xếp</th>
                                <th class="text-truncate">Trạng thái</th>
                                <th class="text-truncate">Ngày tạo</th>
                                <th class="text-truncate">Ngày sửa</th>
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
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-truncate">{{ @$item->name }}</h6>
                                                <span class="small">{{ @$item->alias }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div
                                                style="background-color: {{ $item->code }}; width: 15px; height: 15px; border-radius: 30px; border: 1px solid #ccc">
                                            </div>
                                            <span class="ms-3">{{ $item->code }}</span>

                                        </div>
                                    </td>
                                    <td>{{ $item->ordering }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input data-model="{{ $model }}" data-id="{{ $item->id }}"
                                                data-link="{{ $item->published == 1 ? '/api/active' : '/api/unactive' }}"
                                                id="published" class="form-check-input form-check-input-status float-start"
                                                type="checkbox" value="1" name="published" role="switch"
                                                {{ $item->published == 1 ? 'checked' : null }}>
                                        </div>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td class="d-flex">
                                        <a style="width: 30px" class="dropdown-item p-1"
                                            href="{{ route('edit_color', ['id' => $item->id]) }}">
                                            <i class="mdi mdi-pencil-outline me-1"></i>
                                        </a>
                                        <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                            data-link="{{ route('delete_color', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.product.index']) }}">
                                            <i class="mdi mdi-trash-can-outline me-1"></i>
                                        </a>
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
