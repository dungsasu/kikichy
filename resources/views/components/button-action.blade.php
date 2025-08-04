<div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 1020; background-color: #ffffff;">
    <div class="card-body py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 8px; height: 8px;"></div>
                </div>
                <h6 class="mb-0 text-dark fw-semibold">Danh mục</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if (strpos(@$show, 'create') !== false)
                    <a href="{{ $create ? $create : route($viewCurrent . '.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Thêm mới"
                        class="btn btn-sm btn-success d-flex align-items-center justify-content-center waves-effect waves-light"
                        style="width: 36px; height: 36px; border-radius: 8px;">
                        <i class="mdi mdi-plus fs-5"></i>
                    </a>
                @endif
                @if (strpos(@$show, 'duplicate') !== false)
                    <button data-model="{{ $model }}" data-link="{{ '/api/duplicate' }}" id="dupplicate-button"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Nhân bản" type="button"
                        class="button-action btn btn-sm btn-outline-info d-flex align-items-center justify-content-center waves-effect"
                        style="width: 36px; height: 36px; border-radius: 8px;">
                        <span class="mdi mdi-content-copy fs-6"></span>
                    </button>
                @endif
                @if (strpos(@$show, 'active') !== false)
                    <button data-model="{{ $model }}" data-link="{{ '/api/active' }}" id="active-button"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Kích hoạt" type="button"
                        class="button-action btn btn-sm btn-outline-success d-flex align-items-center justify-content-center waves-effect"
                        style="width: 36px; height: 36px; border-radius: 8px;">
                        <span class="mdi mdi-checkbox-marked-circle-outline fs-6"></span>
                    </button>
                @endif
                @if (strpos(@$show, 'unactive') !== false)
                    <button data-model="{{ $model }}" data-link="{{ '/api/unactive' }}" id="unactive-button"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-original-title="Ngừng kích hoạt" type="button"
                        class="button-action btn btn-sm btn-outline-warning d-flex align-items-center justify-content-center waves-effect"
                        style="width: 36px; height: 36px; border-radius: 8px;">
                        <span class="mdi mdi-circle-off-outline fs-6"></span>
                    </button>
                @endif
                {{ $slot }}
                @if (strpos(@$show, 'delete') !== false)
                    <button data-model="{{ $model }}" data-link="{{ '/api/delete' }}" id="delete-button"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Xoá"
                        class="button-action btn btn-sm btn-danger d-flex align-items-center justify-content-center waves-effect"
                        style="width: 36px; height: 36px; border-radius: 8px;">
                        <span class="mdi mdi-delete fs-6"></span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
