@extends('admin.contentNavLayout')

@section('title', 'Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-center align-items-center flex-column align-items-column">
        <img src="{{ asset('images/access_denied.webp') }}" alt="access_denied">
        <p>Bạn không có quyền truy cập vào trang này!</p>
    </div>
@endsection
