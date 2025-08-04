@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'danh sách liên hệ';
@endphp

@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <x-button-action show="create,duplicate,active,unactive,delete" create="{{ route('admin.contents.categories.create') }}"
        :model="$model" :view="$view" />

    <div class="col-md-12">
        <div class="card mb-4">
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
                            <th class="text-truncate">Tên người liên hệ</th>
                            <th class="text-truncate">Số điện thoại</th>
                            <th class="text-truncate">Email</th>
                            <th class="text-truncate">Tiêu đề</th>
                            <th class="text-truncate">Trạng thái</th>
                            <th class="text-truncate">Ngày gửi</th>
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
                                <td class="text-truncate"><a
                                        href="{{ route('admin.contact.edit', ['id' => $item->id]) }}">{{ @$item->name }}</a>
                                </td>
                                <td class="text-truncate">{{ @$item->phone }}</td>
                                <td class="text-truncate">{{ @$item->email }}</td>
                                <td class="text-truncate">{{ @$item->title }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input data-model="{{ $model }}" data-id="{{ $item->id }}"
                                            data-link="{{ $item->published == 1 ? '/api/active' : '/api/unactive' }}"
                                            id="published" class="form-check-input form-check-input-status float-start"
                                            type="checkbox" value="1" name="published" role="switch"
                                            {{ $item->published == 1 ? 'checked' : null }}>
                                    </div>
                                </td>
                                <td class="text-truncate">{{ @$item->created_at }}
                                <td>
                                    <div class="d-flex">
                                        <a style="width: 30px" class="dropdown-item p-1"
                                            href="{{ route('admin.contact.edit', ['id' => $item->id]) }}">
                                            <i class="mdi mdi-pencil-outline me-1"></i>
                                        </a>
                                        <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                            data-link="{{ route('admin.contact.delete', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.product.index']) }}">
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

@endsection
