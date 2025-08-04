@extends('admin.contentNavLayout')

@section('title', 'Chi tiết Log SMS')

@section('page-style')
    <style>
        .detail-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #333;
            margin-bottom: 15px;
        }
        .status-success {
            color: #28a745;
            font-weight: 600;
        }
        .status-failed {
            color: #dc3545;
            font-weight: 600;
        }
        .json-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chi tiết Log SMS #{{ $data->id }}</h5>
                    <a href="{{ route('admin.smshistory.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Thông tin cơ bản -->
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="text-primary mb-3">Thông tin cơ bản</h6>
                                
                                <div class="detail-label">Số điện thoại:</div>
                                <div class="detail-value">{{ $data->phone }}</div>
                                
                                <div class="detail-label">Nhà mạng:</div>
                                <div class="detail-value">
                                    <span class="badge bg-secondary">{{ $data->telco ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="detail-label">Loại SMS:</div>
                                <div class="detail-value">{{ $data->type_label }}</div>
                                
                                <div class="detail-label">Người gửi:</div>
                                <div class="detail-value">{{ $data->sender }}</div>
                                
                                <div class="detail-label">Request ID:</div>
                                <div class="detail-value">
                                    <code>{{ $data->request_id }}</code>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin kỹ thuật -->
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="text-primary mb-3">Thông tin kỹ thuật</h6>
                                
                                <div class="detail-label">Sử dụng Unicode:</div>
                                <div class="detail-value">
                                    <span class="badge bg-{{ $data->use_unicode ? 'success' : 'secondary' }}">
                                        {{ $data->use_unicode ? 'Có' : 'Không' }}
                                    </span>
                                </div>
                                
                                <div class="detail-label">Độ dài tin nhắn:</div>
                                <div class="detail-value">{{ $data->msg_length }} ký tự</div>
                                
                                <div class="detail-label">Số tin nhắn:</div>
                                <div class="detail-value">{{ $data->mt_count }}</div>
                                
                                <div class="detail-label">Tài khoản:</div>
                                <div class="detail-value">{{ $data->account ?? 'N/A' }}</div>
                                
                                <div class="detail-label">Referent ID:</div>
                                <div class="detail-value">
                                    <code>{{ $data->referent_id ?? 'N/A' }}</code>
                                </div>
                            </div>
                        </div>

                        <!-- Nội dung tin nhắn -->
                        <div class="col-12">
                            <div class="detail-card">
                                <h6 class="text-primary mb-3">Nội dung tin nhắn</h6>
                                <div class="alert alert-light">
                                    {{ $data->message }}
                                </div>
                            </div>
                        </div>

                        <!-- Trạng thái và lỗi -->
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="text-primary mb-3">Trạng thái</h6>
                                
                                <div class="detail-label">Trạng thái:</div>
                                <div class="detail-value">
                                    <span class="status-{{ $data->status }}">
                                        {{ $data->status_label }}
                                    </span>
                                </div>
                                
                                @if($data->error_code)
                                    <div class="detail-label">Mã lỗi:</div>
                                    <div class="detail-value">
                                        <span class="badge bg-danger">{{ $data->error_code }}</span>
                                    </div>
                                    
                                    <div class="detail-label">Thông báo lỗi:</div>
                                    <div class="detail-value text-danger">{{ $data->error_message }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Thời gian -->
                        <div class="col-md-6">
                            <div class="detail-card">
                                <h6 class="text-primary mb-3">Thời gian</h6>
                                
                                <div class="detail-label">Thời gian tạo:</div>
                                <div class="detail-value">{{ $data->created_at->format('d/m/Y H:i:s') }}</div>
                                
                                <div class="detail-label">Thời gian cập nhật:</div>
                                <div class="detail-value">{{ $data->updated_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                        </div>

                        <!-- Response Data -->
                        @if($data->response_data)
                        <div class="col-12">
                            <div class="detail-card">
                                <h6 class="text-primary mb-3">Response Data</h6>
                                <div class="json-container">
                                    {{ $data->formatted_response_data }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.smshistory.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left me-1"></i>Quay lại danh sách
                                </a>
                                @if($data->status === 'failed')
                                    <button class="btn btn-warning" onclick="resendSms({{ $data->id }})">
                                        <i class="mdi mdi-send me-1"></i>Gửi lại
                                    </button>
                                @endif
                                <button class="btn btn-info" onclick="window.print()">
                                    <i class="mdi mdi-printer me-1"></i>In
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
    function resendSms(id) {
        if (confirm('Bạn có chắc chắn muốn gửi lại SMS này?')) {
            fetch(`/admin/smshistory/resend/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã gửi lại SMS thành công');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi gửi lại SMS');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi lại SMS');
            });
        }
    }
</script>
@endsection
