@extends('admin.contentNavLayout')

@php
    $title = (@$item_contact->id ? 'Chi tiết' : 'Thêm') . ' ' . 'danh sách liên hệ';
@endphp

@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')

    <div class="d-flex justify-content-between">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light"></span> {{ $title }}
        </h4>
        <div class="mt-4 mb-4">
            <button type="reset" onclick="window.location.href='{{ route($view . '.index') }}'"
                class="btn btn-outline-secondary">Đóng</button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body pt-2 mt-1">
                <form id="formAccountSettings" method="POST" action={{ route('save_customer') }}
                    enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ @$item_contact->id }}" />
                    @csrf

                    <input type="hidden" name="id" value="{{ @$item_contact->id }}">
                    <div class="row mt-2 gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" name="name"
                                    value="<?php echo @$item_contact->name; ?>" placeholder="<?php echo @$item_contact->name; ?>" readonly />
                                <label for="firstName">Tên người liên hệ</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="phone" name="phone"
                                    value="{{ @$item_contact->phone }}" placeholder="{{ @$item_contact->phone }}"
                                    readonly />
                                <label for="email">Số điện thoại</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ @$item_contact->email }}" placeholder="{{ @$item_contact->email }}"
                                    readonly />
                                <label for="email">Emai;</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="ordering" name="ordering"
                                    value="{{ @$item_contact->ordering ? @$item_contact->ordering : @$maxOrdering }}"
                                    placeholder="{{ @$item_contact->ordering }}" />
                                <label for="email">Sắp xếp</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="form-floating form-floating-outline">
                            <textarea id="title" style="height: 50px" name="title" class="form-control" placeholder="Tiêu đề" aria-label=""
                                aria-describedby="" readonly>{{ @$item_contact->title }}</textarea>
                            <label for="comment">Tiêu đề</label>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="form-floating form-floating-outline">
                            <textarea id="comment" style="height: 200px" name="content" class="form-control" placeholder="Nội dung" aria-label=""
                                aria-describedby="" readonly>{{ @$item_contact->content }}</textarea>
                            <label for="comment">Nội dung</label>
                        </div>
                    </div>
                    {{-- <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <div class="button-wrapper">
                                <div class="form-check form-switch mt-4">
                                    <label for="publish">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$item_contact->published || @!$item_contact->id ? 'checked' : null }}>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </form>
            </div>
        </div>

    </div>

@endsection
