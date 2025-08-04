@extends('admin.contentNavLayout')

@section('title', 'Thành phố')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('assets/admin/js/admin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    <div class="row gy-4">
        @if (@$show_button_action)
            <x-button-action show="create,duplicate,active,unactive,delete" create="{{ route('admin.city.create') }}" :model="$model" />
        @endif
        <x-filter prefix="{{ $prefix }}" :filter-value="$filter" field="image" />
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <span class="form-check mb-0">
                                        <input id="selectAllRecord" class="form-record form-check-input me-1"
                                            type="checkbox" value="">
                                    </span>
                                </th>
                                <th class="text-truncate">Tên thành phố</th>
                                <th class="text-truncate">Quốc gia</th>
                                <th class="text-truncate">Alias</th>
                                <th class="text-truncate">Trạng thái</th>
                                <th class="text-truncate">Thứ tự</th>
                                <th class="text-truncate">Ngày tạo</th>
                                <th class="text-truncate">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                        
                                            <div class="d-flex flex-column">
                                                <span class="text-truncate fw-medium">{{ $item->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->country ? $item->country->name : 'N/A' }}</td>
                                    <td>{{ $item->alias }}</td>
                                    <td>
                                        @if ($item->published == 1)
                                            <span class="badge bg-label-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-label-danger">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->ordering }}</td>
                                    <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('admin.city.edit', $item->id) }}"
                                                class="text-body me-1">
                                                <i class="mdi mdi-pencil-outline mdi-20px"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $item->id }}"
                                                class="text-body delete-record">
                                                <i class="mdi mdi-delete-outline mdi-20px"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $list->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa thành phố này không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" action="{{ route('admin.city.delete') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="id" id="deleteId">
                        <input type="hidden" name="agreeDelete" value="1">
                        <input type="hidden" name="route" value="admin.city.index">
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const deleteIdInput = document.getElementById('deleteId');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const itemId = button.getAttribute('data-id');
                deleteIdInput.value = itemId;
            });

            // Handle form submission
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch(deleteForm.action, {
                    method: 'POST',
                    body: new FormData(deleteForm),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(deleteModal);
                        modal.hide();
                        
                        // Show success message and reload
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi xóa!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa!');
                });
            });
        });
    </script>

@endsection
