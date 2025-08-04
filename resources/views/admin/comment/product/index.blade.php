@extends('admin.contentNavLayout')

@section('title', 'Danh sách đánh giá') 

@section('content')
    <div class="row gy-4">
        {{-- @if ($show_button_action)
            <x-button-action show="create,duplicate,active,unactive,delete" :view="$view" :model="$model" />
        @endif --}}
        <div class="col-12">
            <x-filter prefix="{{ $prefix }}" :filter-value="$filter"   />
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
                                <th class="text-truncate">Sản phẩm</th>
                                <th class="text-truncate">Trung bình sao</th>
                                <th class="text-truncate">Tổng đánh giá</th>
                                <th class="text-truncate">Tổng hữu ích</th>
                                <th class="text-truncate">Chưa trả lời</th>
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
                                        <div class="">
                                            <a href="{{ @$item->product->href }}" target="_blank">
                                                {{ @$item->product->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-end gap-1">
                                            {{ round($item->avg_rate, 1) }} 
                                            <i class="menu-icon tf-icons mdi mdi-star" style="color: #F8B52B;"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <span>
                                            {{ $item->total_comment }}
                                        </span>
                                    </td> 
                                    <td>
                                        <span>
                                            {{ $item->total_helpfull }}
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            {{ $item->total_unread }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 35px" class="dropdown-item p-1"
                                                href="{{ route($view . '.edit', ['id' => $item->product_id]) }}">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            {{-- <a href="#" style="width: 35px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route($view . '.delete', ['id' => $item->product_id, 'agreeDelete' => $item->product_id, 'route' => 'admin.banner.index']) }}">
                                                <i class="mdi mdi-trash-can-outline me-1"></i>
                                            </a> --}}
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
    <div class="d-flex justify-content-between mt-3">
        <div></div>
        {{ $list->links() }}
    </div>

@endsection
