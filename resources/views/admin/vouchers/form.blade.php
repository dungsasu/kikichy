@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'voucher';
@endphp
@section('title', $title)


@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light"></span> {{ @$data->id ? 'Chi tiết' : 'Thêm' }} voucher
        </h4>
        <div class="mt-4 mb-4">

            @if (@!$data->id)
                <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
                <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
                <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
            @endif

            <button type="reset" onclick="window.location.href='{{ route($view . '.index') }}'"
                class="btn btn-outline-secondary">Đóng</button>
        </div>
    </div>
    <div class="card">
        <div class="card-body pt-2 mt-1">
            <form id="formAccountSettings" method="POST" action={{ route($view . '.save') }} enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ @$data->id }}" />
                <input type="hidden" name="type" value="5" />
                @csrf
                <input type="hidden" name="id" value="{{ @$data->id }}">
                <div class="row mt-2 gy-4">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="name" name="name"
                                value="<?php echo @$data->name; ?>" placeholder="<?php echo @$data->name; ?>" autofocus />
                            <label for="firstName">Tên voucher</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="members-select" name="customer" class="form-select select2 form-select-sm"
                                style="width: 100%;">
                                <!-- Initial options will be loaded here -->
                            </select>
                            <label for="firstName">Áp dụng cho thành viên</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 gy-4">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="code" name="code"
                                value="<?php echo @$data->code ? @$data->code : $code; ?>" placeholder="<?php echo @$data->code; ?>" autofocus />
                            <label for="firstName">Mã voucher</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="number" id="discount" name="discount"
                                value="<?php echo @$data->discount ? @$data->discount : 0; ?>" placeholder="<?php echo @$data->discount; ?>" autofocus />
                            <label for="firstName">Giá trị giảm (VND)</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 gy-4">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="bill_from" name="bill_from"
                                value="<?php echo @$data->bill_from ? @$data->bill_from : 0; ?>" placeholder="<?php echo @$data->bill_from; ?>" autofocus />
                            <label for="firstName">Giá trị hoá đơn từ</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="number" id="bill_to" name="bill_to"
                                value="<?php echo @$data->bill_to ? @$data->bill_to : 0; ?>" placeholder="<?php echo @$data->bill_to; ?>" autofocus />
                            <label for="firstName">Giá trị hoá đơn đến</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 gy-4">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            @php
                                $currentDate = date('Y-m-d');
                                $dateStart = isset($data->date_start) ? $data->date_start : $currentDate;
                            @endphp
                            <input class="form-control" type="date" id="date_start" name="date_start"
                                value="{{ $dateStart }}" placeholder="{{ $dateStart }}" autofocus />
                            <label for="firstName">Từ ngày</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            @php
                                $currentDate = date('Y-m-d');
                                $nextDay = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                                $dateEnd = isset($data->date_expiration) ? $data->date_expiration : $nextDay;
                            @endphp
                            <input class="form-control" type="date" id="date_expiration" name="date_expiration"
                                value="<?php echo @$dateEnd; ?>" placeholder="<?php echo @$dateEnd; ?>" autofocus />
                            <label for="firstName">Đến ngày</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-switch mt-4">
                            <label for="ad_ckc_yn">ad_ckc_yn</label>
                            <input form="formAccountSettings" id="ad_ckc_yn" class="form-check-input float-start"
                                type="checkbox" value="1" name="ad_ckc_yn" role="switch"
                                {{ @$data->ad_ckc_yn ? 'checked' : null }}>
                        </div>
                        <div class="form-check form-switch mt-4">
                            <label for="ad_ckvip_yn">ad_ckvip_yn</label>
                            <input form="formAccountSettings" id="ad_ckvip_yn" class="form-check-input float-start"
                                type="checkbox" value="1" name="ad_ckvip_yn" role="switch"
                                {{ @$data->ad_ckvip_yn ? 'checked' : null }}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mt-4">
                            <label for="ad_cktang_yn">ad_cktang_yn</label>
                            <input form="formAccountSettings" id="ad_cktang_yn" class="form-check-input float-start"
                                type="checkbox" value="1" name="ad_cktang_yn" role="switch"
                                {{ @$data->ad_cktang_yn ? 'checked' : null }}>
                        </div>
                        <div class="form-check form-switch mt-4">
                            <label for="ad_ckcombo_yn">ad_ckcombo_yn</label>
                            <input form="formAccountSettings" id="ad_ckcombo_yn" class="form-check-input float-start"
                                type="checkbox" value="1" name="ad_ckcombo_yn" role="switch"
                                {{ @$data->ad_ckcombo_yn ? 'checked' : null }}>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalCenterTitle">Đổi mật khẩu</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('change_password') }}" method="POST" id="change-password">
                <input type="hidden" name="id" value="{{ @$data->id }}">

                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4 mt-2">
                            <div class="form-floating form-floating-outline">
                                <input type="password" id="old-pass" name="old-password" class="form-control"
                                    placeholder="Mật khẩu cũ">
                                <label for="nameWithTitle">Mật khẩu cũ</label>
                            </div>
                            <div class="form-floating form-floating-outline mt-3">
                                <input type="password" id="new-pass" name="password" class="form-control"
                                    placeholder="Mật khẩu cũ">
                                <label for="nameWithTitle">Mật khẩu mới</label>
                            </div>
                            <div class="form-floating form-floating-outline mt-3">
                                <input type="password" id="confirm-pass" name="confirm-pass" class="form-control"
                                    placeholder="Mật khẩu cũ">
                                <label for="nameWithTitle">Nhập lại mật khẩu</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>

        </div>
    </div>
</div>


@section('script_page')
    <script>
        $(document).ready(function() {
            $('#confirm-pass').on('blur', function() {
                var newPassword = $('#new-pass').val();
                var confirmPassword = $('#confirm-pass').val();
                if (newPassword !== confirmPassword) {
                    alert('Mật khẩu mới và mật khẩu nhập lại không khớp.');
                }
            });


            let page = 1;

            function loadMembers(page) {
                $('#members-select').select2({
                    ajax: {
                        url: '/api/members',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.data.map(function(member) {
                                    return {
                                        id: member.id,
                                        text: member.name + ' - ' + (member.ma_kh || member.ma_the || 'N/A') + ' - ' + (member.phone || 'N/A')
                                    };
                                }),
                                pagination: {
                                    more: data.next_page_url !== null
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1,
                    placeholder: 'Chọn thành viên',
                    allowClear: true
                });

                // Set selected member if editing
                @if(isset($selectedMember) && $selectedMember)
                    var selectedOption = new Option(
                        '{{ $selectedMember->name }} - {{ $selectedMember->ma_kh ?: $selectedMember->ma_the }} - {{ $selectedMember->phone ?: "N/A" }}',
                        '{{ $selectedMember->id }}',
                        true,
                        true
                    );
                    $('#members-select').append(selectedOption).trigger('change');
                @endif
            }
            // Load initial members
            loadMembers(page);

        });
        $(document).on('click', '.delete-icon', function() {
            $(this).parent().remove();
        });

        $('.delete-icon-ajax').click(function() {
            let _this = this;
            let id_image = $(this).data('id-image');
            $.ajax({
                url: '/admin_baohieman/members/galleryImages/' + id_image,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $(_this).parent().remove();
                    } else {
                        // handle error
                    }
                    alert(response.message);

                }
            });
        });
    </script>
@endsection
