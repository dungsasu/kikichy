    <link rel="stylesheet" href="{{ asset(mix('assets/css/manage_account.css')) }}">
    {{-- MODAL ADD ADDRESS --}}
    <div class="modal fade" id="user_address_add" tabindex="-1" aria-hidden="true">
        <div class="control_wide_modal_address modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="add_member_address" action="{{ route('client.user_address.save_address') }}"
                        method="POST">
                        @csrf
                        <button type="button" class="btn-close btn_close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="title_modal">Thêm địa chỉ</div>
                        <div class="form_input_address">
                            <input type="hidden" name="id" id="id_address">

                            <div class="">
                                <input name="name_member" id="name" class="input_item" type="text"
                                    placeholder="Họ và tên">
                            </div>
                            <div class="">
                                <input name="phone" id="phone" class="input_item" type="text"
                                    placeholder="Số điện thoại">
                            </div>
                            <div class="">
                                <input name="address" id="address" class="input_item" type="text"
                                    placeholder="Địa chỉ(Ví dụ: Số 23, ngõ 66, Hồ Tùng Mậu...)">
                            </div>
                            <div class="">
                                <select class="input_item" name="city"
                                    data-member-province="{{ @$member->province_id }}" id="provinces">
                                    <option value="0" selected disabled>@translate('Tỉnh/TP')</option>
                                    @foreach ($cities as $item)
                                        <option value="{{ $item->code }}">{{ $item->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="">
                                <select class="input_item" name="district"
                                    data-member-district="{{ @$member->district_id }}" id="districts">
                                    <option value="0" selected>@translate('Quận/Huyện')</option>
                                </select>
                            </div>
                            <div class="">
                                <select class="input_item" name="ward" data-member-ward="{{ @$member->ward_id }}"
                                    id="wards">
                                    <option value="0" selected>@translate('Phường/Xã')</option>
                                </select>
                            </div>
                        </div>
                        <div class="set_as_default">
                            <input type="checkbox" id="set_default">
                            <label for="set_default">Đặt làm địa chỉ mặc định</label>
                        </div>
                        <div class="row_btn">
                            <div class="btn_cancel" data-bs-dismiss="modal">Huỷ</div>
                            <button type="submit" class="btn_submit_add">Thêm</button>
                            {{-- <button data-id-edit="" id="btn_submit_edit" class="btn_submit_edit">Chỉnh sửa</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
