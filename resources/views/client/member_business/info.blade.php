@extends('client.member_business.layout')

@section('title', 'Thông tin liên hệ')

@section('page-title', 'Thông tin liên hệ')

@section('page-content')
    <div class="info-section">
        <form method="POST" action="{{ route('client.business.update_contact_info') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="company_legal_name">Tên pháp lý của công ty <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control-custom normal {{ $errors->has('company_legal_name') ? 'is-invalid' : '' }}"
                                id="company_legal_name" name="company_legal_name"
                                value="{{ old('company_legal_name', Auth::guard('members')->user()->company_legal_name ?? '') }}"
                                placeholder="Vui lòng nhập tên công ty">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                        @if ($errors->has('company_legal_name'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('company_legal_name') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="tel" class="form-control-custom normal {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                id="phone" name="phone"
                                value="{{ old('phone', $contactInfo->phone ?? '') }}"
                                placeholder="Vui lòng nhập số điện thoại">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                        @if ($errors->has('phone'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('phone') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="country">Quốc gia <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <select class="form-control-custom normal {{ $errors->has('country_id') ? 'is-invalid' : '' }}" id="country_id" name="country_id">
                                <option value="">Chọn quốc gia</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" 
                                        {{ (old('country_id', $contactInfo && $contactInfo->country_id) == $country->id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                        @if ($errors->has('country_id'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('country_id') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="city">Thành phố <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <select class="form-control-custom normal {{ $errors->has('city_id') ? 'is-invalid' : '' }}" id="city_id" name="city_id">
                                <option value="">Chọn thành phố</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ (old('city_id', $contactInfo && $contactInfo->city_id) == $city->id) ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                        @if ($errors->has('city_id'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('city_id') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control-custom normal {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                id="address" name="address"
                                value="{{ old('address', $contactInfo->address ?? '') }}"
                                placeholder="Vui lòng nhập địa chỉ">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                        @if ($errors->has('address'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="website">Website</label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control-custom normal"
                                id="website" name="website"
                                value="{{ old('website', $contactInfo->website ?? '') }}"
                                placeholder="Vui lòng nhập URL website">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="youtube">Youtube</label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control-custom normal"
                                id="youtube" name="youtube"
                                value="{{ old('youtube', $contactInfo->youtube ?? '') }}"
                                placeholder="Vui lòng nhập URL Youtube">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="facebook">Facebook</label>
                        <div class="input-wrapper">
                            <input type="text" class="form-control-custom normal"
                                id="facebook" name="facebook"
                                value="{{ old('facebook', $contactInfo->facebook ?? '') }}"
                                placeholder="Vui lòng nhập URL Facebook">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="instagram">Instagram</label>
                        <div class="input-wrapper">
                            <input type="url" class="form-control-custom normal"
                                id="instagram" name="instagram"
                                value="{{ $contactInfo->instagram ?? '' }}"
                                placeholder="https://www.instagram.com/victoriatour">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="twitter">Twitter</label>
                        <div class="input-wrapper">
                            <input type="url" class="form-control-custom normal"
                                id="twitter" name="twitter"
                                value="{{ $contactInfo->twitter ?? '' }}"
                                placeholder="Vui lòng nhập URL Twitter">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    Lưu lại
                </button>
            </div>
        </form>
    </div>

    <style>
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        
        .form-control-custom.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country_id');
            const citySelect = document.getElementById('city_id');
            
            if (!countrySelect || !citySelect) {
                console.error('Country or City select elements not found');
                return;
            }
            
            countrySelect.addEventListener('change', function() {
                const countryId = this.value;
                console.log('Country changed to:', countryId);
                
                // Clear cities dropdown
                citySelect.innerHTML = '<option value="">Chọn thành phố</option>';
                
                if (countryId) {
                    // Show loading
                    citySelect.innerHTML = '<option value="">Đang tải...</option>';
                    
                    console.log('Fetching cities for country:', countryId);
                    
                    // Fetch cities using GET request
                    fetch('{{ route("client.get_cities_by_country") }}?country_id=' + countryId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        citySelect.innerHTML = '<option value="">Chọn thành phố</option>';
                        
                        if (data.success && data.cities && data.cities.length > 0) {
                            data.cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.appendChild(option);
                            });
                            console.log('Loaded', data.cities.length, 'cities');
                        } else if (data.success && data.cities.length === 0) {
                            citySelect.innerHTML = '<option value="">Không có thành phố nào</option>';
                        } else {
                            citySelect.innerHTML = '<option value="">Lỗi: ' + (data.message || 'Không thể tải dữ liệu') + '</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        citySelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu: ' + error.message + '</option>';
                    });
                }
            });
        });
    </script>
@endsection
