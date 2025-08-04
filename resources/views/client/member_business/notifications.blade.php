@extends('client.member_business.layout')

@section('title', 'Thông báo qua email')

@section('page-title', 'Thông báo qua email')

@section('page-content')
    <div class="info-section">
        <h3>Cài đặt thông báo email</h3>
        <p>Trang cài đặt thông báo qua email đang được phát triển...</p>
        
        <div class="email-settings">
            <div class="row">
                <div class="col-md-8">
                    <h5>Cài đặt nhận thông báo</h5>
                    <form>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="orderNotif" checked>
                            <label class="form-check-label" for="orderNotif">
                                Thông báo khi có đơn hàng mới
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="paymentNotif" checked>
                            <label class="form-check-label" for="paymentNotif">
                                Thông báo khi có thanh toán
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="customerNotif">
                            <label class="form-check-label" for="customerNotif">
                                Thông báo khách hàng mới
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
