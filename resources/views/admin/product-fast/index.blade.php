@extends('admin.contentNavLayout')

@section('title', 'Màu sắc sản phẩm')


@section('content')
    <div class="row gy-4">
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
                                <th class="text-truncate">Mã Fast</th>
                                <th class="text-truncate">Màu sắc</th>
                                <th class="text-truncate">Kích thước</th>
                                <th class="text-truncate">Kho</th>
                                <th class="text-truncate">Ngày tạo</th>
                                <th class="text-truncate">Ngày sửa</th>
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
                                                <h6 class="mb-0 text-truncate">{{ @$item->variant }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div>
                                                {{ $item->color_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div>
                                                {{ $item->size_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            @php
                                                $storeData = json_decode($item->store, true);
                                            @endphp
                                            @foreach ($storeData as $store => $quantity)
                                                {{ $store }}: {{ $quantity }} <br>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex ">
                                            <div>
                                                {{ $item->created_at }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div>
                                                {{ $item->updated_at }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $list->links() }}

        <!--/ Data Tables -->
    </div>
@endsection
