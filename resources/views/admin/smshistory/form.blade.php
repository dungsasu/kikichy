@extends('admin.contentNavLayout')

@section('title', 'Gửi SMS')

@section('page-style')
    <style>
        .sms-form {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .char-counter {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
        .char-counter.warning {
            color: #f39c12;
        }
        .char-counter.danger {
            color: #e74c3c;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gửi SMS mới</h5>
                    <a href="{{ route('admin.smshistory.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('save_smshistory') }}" method="POST" id="smsForm">
                        @csrf
                        @if(isset($data))
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sms-form">
                                    <h6 class="text-primary mb-3">Thông tin người nhận</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                        @if(isset($data))
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $data->phone ?? '') }}" required readonly>
                                        @else
                                            <textarea class="form-control" name="phone" rows="3" 
                                                placeholder="Nhập số điện thoại (mỗi số một dòng)" required>{{ old('phone') }}</textarea>
                                            <small class="text-muted">Có thể nhập nhiều số điện thoại, mỗi số một dòng</small>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Loại SMS</label>
                                        <select name="type" class="form-select" {{ isset($data) ? 'disabled' : '' }}>
                                            <option value="1" {{ old('type', $data->type ?? 1) == 1 ? 'selected' : '' }}>SMS Thông báo</option>
                                            <option value="2" {{ old('type', $data->type ?? 1) == 2 ? 'selected' : '' }}>SMS Khuyến mãi</option>
                                            <option value="3" {{ old('type', $data->type ?? 1) == 3 ? 'selected' : '' }}>SMS OTP</option>
                                            <option value="4" {{ old('type', $data->type ?? 1) == 4 ? 'selected' : '' }}>SMS Chăm sóc khách hàng</option>
                                        </select>
                                        @if(isset($data))
                                            <input type="hidden" name="type" value="{{ $data->type }}">
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Người gửi</label>
                                        <input type="text" class="form-control" name="sender" 
                                            value="{{ old('sender', $data->sender ?? 'DMCFASHION') }}" maxlength="50" 
                                            {{ isset($data) ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="sms-form">
                                    <h6 class="text-primary mb-3">Nội dung tin nhắn</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tin nhắn <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message" rows="6" 
                                            placeholder="Nhập nội dung tin nhắn..." required 
                                            id="messageContent" {{ isset($data) ? 'readonly' : '' }}>{{ old('message', $data->message ?? '') }}</textarea>
                                        <div class="char-counter" id="charCounter">0/160 ký tự</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="use_unicode" value="1" id="useUnicode"
                                                        {{ old('use_unicode') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="useUnicode">
                                                        Sử dụng Unicode
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Referent ID</label>
                                                <input type="text" class="form-control" name="referent_id" 
                                                    value="{{ old('referent_id') }}" placeholder="Tùy chọn">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <small>
                                            <strong>Lưu ý:</strong>
                                            <ul class="mb-0 mt-1">
                                                <li>SMS thường: tối đa 160 ký tự</li>
                                                <li>SMS Unicode: tối đa 70 ký tự</li>
                                                <li>Tin nhắn dài sẽ được chia thành nhiều SMS</li>
                                            </ul>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    {{-- <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-send me-1"></i>Gửi SMS
                                    </button> --}}
                                    <button type="button" class="btn btn-info" onclick="previewSms()">
                                        <i class="mdi mdi-eye me-1"></i>Xem trước
                                    </button>
                                    <a href="{{ route('admin.smshistory.index') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-arrow-left me-1"></i>Hủy
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xem trước SMS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Người gửi:</strong> <span id="previewSender"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Số điện thoại:</strong> <span id="previewPhone"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Nội dung:</strong>
                        <div class="alert alert-light mt-2" id="previewMessage"></div>
                    </div>
                    <div class="mb-3">
                        <strong>Thống kê:</strong>
                        <ul class="mb-0">
                            <li>Độ dài: <span id="previewLength"></span> ký tự</li>
                            <li>Số tin nhắn: <span id="previewCount"></span></li>
                            <li>Số điện thoại: <span id="previewPhoneCount"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Gửi SMS</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
    const messageInput = document.getElementById('messageContent');
    const charCounter = document.getElementById('charCounter');
    const useUnicode = document.getElementById('useUnicode');

    function updateCharCounter() {
        const message = messageInput.value;
        const isUnicode = useUnicode.checked;
        const maxChars = isUnicode ? 70 : 160;
        const length = message.length;
        const smsCount = Math.ceil(length / maxChars) || 1;

        charCounter.textContent = `${length}/${maxChars} ký tự (${smsCount} SMS)`;
        
        // Update color based on length
        charCounter.className = 'char-counter';
        if (length > maxChars * 0.8) {
            charCounter.classList.add('warning');
        }
        if (length > maxChars) {
            charCounter.classList.add('danger');
        }
    }

    messageInput.addEventListener('input', updateCharCounter);
    useUnicode.addEventListener('change', updateCharCounter);

    function previewSms() {
        const form = document.getElementById('smsForm');
        const formData = new FormData(form);
        
        const sender = formData.get('sender') || 'DMCFASHION';
        const phones = formData.get('phone').split('\n').filter(p => p.trim()).length;
        const message = formData.get('message');
        const isUnicode = formData.get('use_unicode') === '1';
        const maxChars = isUnicode ? 70 : 160;
        const smsCount = Math.ceil(message.length / maxChars) || 1;

        document.getElementById('previewSender').textContent = sender;
        document.getElementById('previewPhone').textContent = phones + ' số';
        document.getElementById('previewMessage').textContent = message;
        document.getElementById('previewLength').textContent = message.length;
        document.getElementById('previewCount').textContent = smsCount;
        document.getElementById('previewPhoneCount').textContent = phones;

        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    function submitForm() {
        document.getElementById('smsForm').submit();
    }

    // Initialize counter
    updateCharCounter();
</script>
@endsection
