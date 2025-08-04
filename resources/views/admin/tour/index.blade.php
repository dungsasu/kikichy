@extends('admin.contentNavLayout')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="row gy-4">
        <x-button-action show="create,duplicate,active,unactive,delete" create="{{ route($view . '.create') }}"
            :controller="@$controller" :model="$model">
        </x-button-action>
        <x-filter prefix="{{ $prefix }}" :categories="$categories" :filter-value="$filter" field="image">
            @php
                $gmc = [
                    null => 'Google Merchant Center',
                    0 => 'Không',
                    1 => 'Có',
                ];
            @endphp
            <div class="col-md-3 ms-3">
                <select name="{{ $prefix ?? '' }}_gmc_filter" aria-controls="DataTables_Table_0"
                    class="form-select select2 form-select-sm">
                    @foreach (@$gmc as $i => $item)
                        <option
                            {{ isset($filter[$prefix . '_gmc_filter']) && $filter[$prefix . '_gmc_filter'] != null && $i == $filter[$prefix . '_gmc_filter'] ? 'selected' : null }}
                            value="{{ $i }}">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </div>
        </x-filter>
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
                                <th class="text-truncate text-center">Tên tour</th>
                                <th class="text-truncate text-center">Xem</th>
                                {{-- <th class="text-truncate" style="min-width: 150px">Fast</th> --}}
                                <th class="text-truncate">Sắp xếp</th>
                                <th class="text-truncate">Danh mục</th>
                                <th class="text-truncate">Trạng thái</th>
                                <th class="text-truncate">Mới</th>
                                <th class="text-truncate">Hot</th>
                                <th class="text-truncate">Ngày tạo</th>
                                <th class="text-truncate">Ngày sửa</th>
                                <th class="text-truncate">Chức năng</th>
                                <th class="text-truncate">id</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($list->isEmpty())
                                <tr>
                                    <td colspan="11" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endif
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}" />
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <a href="{{ route("$view.edit", ['id' => $item->id]) }}"
                                                class="d-flex flex-column align-items-center justify-content-center">
                                                <img src="{{ @$item->image }}" alt="{{ @$item->image }}" class="mb-3"
                                                    style="width: 100px; height:100px; overflow:hidden; object-fit: cover; border-radius: 5px">
                                                <h6 class="mb-0 ">{{ @$item->name }}</h6>
                                                <span class="small">{{ @$item->code }}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->href)
                                            <a target="_blank" href="{{ $item->href }}">
                                                <i class="mdi mdi-earth"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                <i class="mdi mdi-earth-off"></i>
                                            </span>
                                        @endif
                                    </td>
                                    {{-- <td> <a target="_blank" href="{{route('admin.tour-fast.index')}}?code={{$item->code}}">Xem tồn kho</a></td> --}}
                                    <td>{{ $item->ordering }}</td>
                                    <td>{{ @$item->category->name }}</td>
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
                                        <div class="form-check form-switch">
                                            <input data-model="{{ $model }}" data-id="{{ $item->id }}"
                                                data-link="{{ '/api/new' }}" id="new"
                                                class="form-check-input form-check-input-status float-start" type="checkbox"
                                                value="1" name="new" role="switch"
                                                {{ $item->new == 1 ? 'checked' : null }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input data-model="{{ $model }}" data-id="{{ $item->id }}"
                                                data-link="{{ '/api/hot' }}" id="hot"
                                                class="form-check-input form-check-input-status float-start" type="checkbox"
                                                value="1" name="hot" role="switch"
                                                {{ $item->hot == 1 ? 'checked' : null }}>
                                        </div>
                                    </td>

                                    <td class="text-truncate text-center">
                                        <div>{{ date('Y-m-d', strtotime($item->created_at)) }}</div>
                                        <div>{{ date('H:i:s', strtotime($item->created_at)) }}</div>
                                    </td>
                                    <td class="text-truncate text-center">
                                        <div>{{ date('Y-m-d', strtotime($item->updated_at)) }}</div>
                                        <div>{{ date('H:i:s', strtotime($item->updated_at)) }}</div>
                                    </td>
                                    <td>
                                        <span class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route("$view.edit", ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route("$view.delete", ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.tour.index']) }}">
                                                <i class="mdi mdi-trash-can-outline me-1"></i>
                                            </a>
                                        </span>
                                    </td>
                                    <td>{{ $item->id }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $list->links() }}
    </div>
@endsection
