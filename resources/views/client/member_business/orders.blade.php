@extends('client.member_business.layout')

@section('title', 'Trang điều hành')

@section('page-title', 'Trang điều hành')

@section('style_page')
    @parent
    <style>
        .alert {
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
@endsection
@section('page-content')
    <!-- Flash Messages -->
   

    <div class="info-section">
        <form action="{{ route('client.business.orders.save') }}" method="POST" enctype="multipart/form-data"
            id="formAccountSettings">
            @csrf

            <!-- Logo công ty của bạn -->
            <div class="company-logo-section mb-4">
                <h6 class="fw-bold mb-3">Logo công ty của bạn</h6>
                <div class="row">
                    <div class="col-md-6">
                        <x-choose-file title="" :type="'Images'" id="company_logo" field="logo"
                            :dataComponent="$memberOrder" />
                    </div>
                </div>
            </div>
            <!-- Điểm nổi bật -->
            <div class="highlight-section mb-4">
                <x-editor_v2 name="order_description" id="order_description" title="Điểm nổi bật"
                    content="{{ $memberOrder->order_description ?? '' }}" />
            </div>
            <!-- Mô tả doanh nghiệp -->
            <div class="description-section mb-4">
                <x-editor_v2 name="order_summary_business" id="order_summary_business" title="Mô tả doanh nghiệp"
                    content="{{ $memberOrder->order_summary_business ?? '' }}" />
            </div>
            <!-- Nhóm tuổi được phép -->
            <div class="age-group-section">
                <label class="form-label-big fw-bold">Nhóm tuổi được phép <i
                        class="fas fa-question-circle text-muted"></i></label>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Độ tuổi tối thiểu được phép</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="min_age" class="form-control"
                                value="{{ $memberOrder->min_age ?? 1 }}" min="1" max="100" step="1">
                            <div class="number-controls">
                                <button type="button" onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Độ tuổi tối đa được phép</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="max_age" class="form-control"
                                value="{{ $memberOrder->max_age ?? 80 }}" min="1" max="100" step="1">
                            <div class="number-controls">
                                <button type="button" onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nhóm tuổi trung bình</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="avg_age" class="form-control"
                                value="{{ $memberOrder->avg_age ?? 30 }}" min="1" max="100" step="1">
                            <div class="number-controls">
                                <button type="button" onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kích thước nhóm -->
            <div class="group-size-section">
                <label class="form-label-big fw-bold">Kích thước nhóm <i
                        class="fas fa-question-circle text-muted"></i></label>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Kích thước nhóm tối thiểu</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="min_group_size" class="form-control"
                                value="{{ $memberOrder->min_group_size ?? 1 }}" min="1" max="1000"
                                step="1">
                            <div class="number-controls">
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kích thước nhóm tối đa</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="max_group_size" class="form-control"
                                value="{{ $memberOrder->max_group_size ?? 20 }}" min="1" max="1000"
                                step="1">
                            <div class="number-controls">
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kích thước nhóm trung bình</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="avg_group_size" class="form-control"
                                value="{{ $memberOrder->avg_group_size ?? 4 }}" min="1" max="1000"
                                step="1">
                            <div class="number-controls">
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Đánh cho chuyến đi riêng tư -->
            <div class="private-trip-section">
                <label class="form-label-big fw-bold">Đánh cho chuyến đi riêng tư <i
                        class="fas fa-question-circle text-muted"></i></label>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Kích thước nhóm tối thiểu</label>
                        <div class="number-input-wrapper">
                            <input type="number" name="private_min_size" class="form-control"
                                value="{{ $memberOrder->private_min_size ?? 1 }}" min="1" max="1000"
                                step="1">
                            <div class="number-controls">
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepUp()">▲</button>
                                <button type="button"
                                    onclick="this.parentNode.previousElementSibling.stepDown()">▼</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nút lưu -->
            <div class="save-section mt-3">
                <button type="button" class="btn btn-danger px-4" id="save-btn">
                    <i class="fas fa-save me-2"></i>Lưu lại
                </button>
            </div>
        </form>
    </div>

@endsection

@push('push_script')
    <script>
        document.getElementById('save-btn').addEventListener('click', function() {
            // Update editor values before submitting
            void 0 !== window.editors && 
            null !== window.editors && 
            Object.keys(window.editors).length > 0 && 
            Object.keys(window.editors).forEach(t => {
                let e = t.replace("editor_", ""); // Xóa tiền tố "editor_"
                $(`#${e}-value`).val(window.editors[t].getData()) // Gán nội dung vào thẻ có id `${e}-value`
            });
            
            // Submit the form
            document.getElementById('formAccountSettings').submit();
        });
    </script>
@endpush
