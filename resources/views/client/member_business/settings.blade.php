@extends('client.member_business.layout')

@section('title', 'Đặt chỗ và tài chính')

@section('page-title', 'Đặt chỗ và tài chính')

@section('page-content')
    <div class="info-section">
        <h3>Quản lý đặt chỗ và tài chính</h3>
        <p>Trang quản lý đặt chỗ và tài chính đang được phát triển...</p>
        
        <div class="financial-overview">
            <div class="row">
                <div class="col-md-6">
                    <h5>Thống kê đặt chỗ</h5>
                    <div class="booking-stats">
                        <p><strong>Đặt chỗ hôm nay:</strong> 0</p>
                        <p><strong>Đặt chỗ tháng này:</strong> 0</p>
                        <p><strong>Tổng đặt chỗ:</strong> 0</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Thống kê tài chính</h5>
                    <div class="financial-stats">
                        <p><strong>Doanh thu hôm nay:</strong> 0 VNĐ</p>
                        <p><strong>Doanh thu tháng:</strong> 0 VNĐ</p>
                        <p><strong>Tổng doanh thu:</strong> 0 VNĐ</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Phương thức thanh toán</h5>
                    <div class="payment-methods">
                        <p>Cài đặt phương thức thanh toán sẽ được bổ sung...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
