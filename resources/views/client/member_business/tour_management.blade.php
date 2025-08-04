@extends('client.layout')
@section('style_page')
    <link rel="stylesheet" href="{{ asset('assets/client/css/general_tour.css') }}">
@endsection

@section('title', 'Quản lý Tour - ' . $config['title'])

@section('layoutContent')
    <div class="container-fluid">
        <div class="nav-container">
            <div class="tour-management-container py-4">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-md-3">
                        <div class="sidebar-menu">
                            <h5 class="font-light mb-3">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.5003 12.917H17.5003M12.5003 16.2503H17.5003M18.3337 6.89199V3.52533C18.3337 2.20033 17.8003 1.66699 16.4753 1.66699H13.1087C11.7837 1.66699 11.2503 2.20033 11.2503 3.52533V6.89199C11.2503 8.21699 11.7837 8.75033 13.1087 8.75033H16.4753C17.8003 8.75033 18.3337 8.21699 18.3337 6.89199ZM8.75033 7.10033V3.31699C8.75033 2.14199 8.21699 1.66699 6.89199 1.66699H3.52533C2.20033 1.66699 1.66699 2.14199 1.66699 3.31699V7.09199C1.66699 8.27533 2.20033 8.74199 3.52533 8.74199H6.89199C8.21699 8.75033 8.75033 8.27533 8.75033 7.10033ZM8.75033 16.4753V13.1087C8.75033 11.7837 8.21699 11.2503 6.89199 11.2503H3.52533C2.20033 11.2503 1.66699 11.7837 1.66699 13.1087V16.4753C1.66699 17.8003 2.20033 18.3337 3.52533 18.3337H6.89199C8.21699 18.3337 8.75033 17.8003 8.75033 16.4753Z"
                                        stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Danh sách Tour
                            </h5>

                            <button class="font-light btn btn-danger w-100 mb-3">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.66667 1.66699V4.16699M13.3333 1.66699V4.16699M2.91667 7.57532H17.0833M11.6667 15.8337C11.6667 17.6746 13.1591 19.167 15 19.167C16.8409 19.167 18.3333 17.6746 18.3333 15.8337C18.3333 13.9927 16.8409 12.5003 15 12.5003M11.6667 15.8337C11.6667 13.9927 13.1591 12.5003 15 12.5003M11.6667 15.8337C11.6667 16.4587 11.8417 17.0503 12.15 17.5503C12.325 17.8503 12.55 18.117 12.8083 18.3337H6.66667C3.75 18.3337 2.5 16.667 2.5 14.167V7.08366C2.5 4.58366 3.75 2.91699 6.66667 2.91699H13.3333C16.25 2.91699 17.5 4.58366 17.5 7.08366V13.6336C16.8917 12.942 16 12.5003 15 12.5003M16.2417 15.8753H13.7583M15 14.6587V17.1503M9.99624 11.417H10.0037M6.91193 11.417H6.91941M6.91193 13.917H6.91941"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Thêm mới Tour
                            </button>
                            <div class="support-section text-start">
                                {!! @$config['content_hotline'] !!}
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-md-9">
                        <div class="tour-form-container">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs tour-tabs mb-4" id="tourTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="package-tour-tab" data-bs-toggle="tab"
                                        data-bs-target="#package-tour" type="button" role="tab">
                                        Package Tour
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tour-tab" data-bs-toggle="tab" data-bs-target="#tour"
                                        type="button" role="tab">
                                        Tour
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="daily-tour-tab" data-bs-toggle="tab"
                                        data-bs-target="#daily-tour" type="button" role="tab">
                                        Daily Tour
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="tourTabContent">
                                <!-- Package Tour Tab -->
                                <div class="tab-pane fade show active" id="package-tour" role="tabpanel">
                                    <form action="{{ route('client.business.tour_management.save') }}" method="POST"
                                        class="tour-form">
                                        @csrf

                                        <!-- AI Section -->
                                        <div class="ai-section mb-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_12316_21553)">
                                                        <path
                                                            d="M20.0411 6.92634C20.9857 7.71155 21.9107 8.54959 22.8107 9.43513C30.6466 17.1625 34.2534 26.305 30.3536 30.1518C28.0643 32.407 23.9376 32.1518 19.1819 29.8613C18.6158 29.5884 18.3801 28.9123 18.6569 28.3542C18.9337 27.7943 19.6194 27.5637 20.1855 27.8366C24.1623 29.7521 27.3265 29.9475 28.7373 28.5585C31.3819 25.9493 28.2355 17.9705 21.1944 11.0301C20.3408 10.1886 19.4623 9.3928 18.5676 8.64986C18.0855 8.25021 18.0248 7.53896 18.4301 7.06361C18.8373 6.58649 19.559 6.52669 20.0411 6.92634ZM25.2177 18.4175C25.6999 18.8207 25.7588 19.5319 25.3499 20.0055C24.5731 20.9087 23.7249 21.8136 22.8106 22.715C14.9729 30.4405 5.69973 33.9966 1.79985 30.1516C-0.487648 27.8963 -0.230506 23.8314 2.09092 19.1445C2.36771 18.5864 3.05165 18.354 3.61769 18.6269C4.18555 18.8998 4.42125 19.5741 4.14445 20.134C2.20338 24.0512 2.00694 27.169 3.41586 28.5581C6.06052 31.1655 14.1532 28.0634 21.1946 21.1215C22.066 20.2624 22.8714 19.4032 23.6071 18.5476C24.016 18.074 24.7356 18.0161 25.2177 18.4175ZM12.9622 2.28543C13.5283 2.55832 13.764 3.23439 13.4872 3.7925C13.2104 4.35236 12.5265 4.58475 11.9586 4.31184C7.98538 2.39811 4.82487 2.20444 3.4155 3.59351C0.769055 6.20093 3.91731 14.1797 10.9584 21.1218C11.8084 21.9616 12.6851 22.7539 13.578 23.4951C14.0602 23.8965 14.1209 24.6078 13.7155 25.0831C13.3084 25.5584 12.5869 25.6201 12.1048 25.2187C11.162 24.437 10.2388 23.5989 9.34052 22.7151C1.50463 14.9878 -2.10222 5.84527 1.79949 2.00031C4.08521 -0.25497 8.20883 -0.00153446 12.9622 2.28543ZM16.1372 12.164C16.3711 12.2238 16.5497 12.4034 16.6068 12.6341L17.08 14.5812C17.1622 14.9175 17.4229 15.1851 17.7622 15.2784L19.5051 15.7608C19.8247 15.8488 20.0104 16.1745 19.9211 16.4897C19.8622 16.6957 19.6943 16.8559 19.4836 16.9052L17.8318 17.296C17.4693 17.3823 17.1908 17.664 17.1104 18.0231L16.6194 20.2432C16.5461 20.5813 16.2069 20.7961 15.864 20.7221C15.6212 20.6693 15.4319 20.4827 15.3801 20.2432L14.889 18.0267C14.8105 17.6658 14.5265 17.3841 14.164 17.2995L12.4533 16.9017C12.1283 16.8277 11.9283 16.5073 12.0051 16.1869C12.0551 15.9756 12.2176 15.8084 12.4283 15.7503L14.148 15.2749C14.4801 15.1834 14.739 14.9246 14.8248 14.5953L15.3462 12.6217C15.4355 12.2802 15.7908 12.0742 16.1372 12.164ZM30.3534 2.00014C32.6427 4.25717 32.3856 8.33659 30.0606 13.0302C29.7838 13.5901 29.098 13.8207 28.532 13.5478C27.9641 13.2749 27.7284 12.6006 28.007 12.0407C29.9516 8.11471 30.1498 4.98632 28.7374 3.59365C26.0909 0.984476 17.9982 4.08838 10.9586 11.0303C10.1015 11.8736 9.29078 12.7433 8.5354 13.6288C8.12826 14.106 7.40863 14.1658 6.9247 13.7662C6.44255 13.3648 6.38006 12.6553 6.7872 12.1782C7.58363 11.2433 8.43899 10.326 9.34075 9.43522C17.1785 1.7097 26.4517 -1.84662 30.3534 2.00014Z"
                                                            fill="black" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_12316_21553">
                                                            <rect width="32" height="32" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                <h6 class="mb-0 ms-1">Cập nhật từ AI</h6>
                                            </div>
                                            <p class="text-muted small mb-3">
                                                Việc tải file chuyến tham quan giờ đây trở nên dễ dàng hơn nhờ sử dụng AI để
                                                nhập thông tin Hành trình của bạn
                                            </p>
                                            <button type="button" class="btn btn-primary font-light">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M20 19C20.5522 19 20.9999 19.4478 21 20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20C3.00008 19.4478 3.44777 19 4 19H20ZM17.707 8.29297C18.0975 8.68347 18.0975 9.3165 17.707 9.70703C17.3165 10.0976 16.6835 10.0976 16.293 9.70703L13 6.41406V16C13 16.5523 12.5523 17 12 17C11.4477 17 11 16.5523 11 16V6.41406L7.70703 9.70703L7.63086 9.77539C7.23809 10.0957 6.65908 10.0731 6.29297 9.70703C5.92692 9.34091 5.90428 8.76189 6.22461 8.36914L6.29297 8.29297L12 2.58594L17.707 8.29297Z"
                                                        fill="white" />
                                                </svg>
                                                Upload file
                                            </button>
                                        </div>

                                        <div class="row">
                                            <!-- Left Column -->
                                            <div class="col-md-12">
                                                <!-- Tour Name -->
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Tên hành trình khách phá (tour, chuyến tham
                                                        quan):</label>
                                                    <input type="text" class="font-light form-control" name="tour_name"
                                                        placeholder="5 Days Bangkok and Koh Samui" required>
                                                </div>
                                            </div>

                                            <!-- Tour Code -->
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Mã Tour của bạn</label>
                                                <input type="text" class="font-light form-control" name="tour_code"
                                                    placeholder="TLBK5D4N" required>
                                            </div>

                                            <!-- Duration -->
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Khoảng thời gian</label>
                                                <div class="input-group">
                                                    <input type="number" class="font-light form-control" name="duration"
                                                        placeholder="5" min="1" required>
                                                    <span class="input-group-text">Ngày</span>
                                                </div>
                                            </div>

                                            <!-- Tour Type -->
                                           

                                            <div class="col-md-12 form-group mb-3">
                                                <label class="form-label">Phong cách trải nghiệm</label>
                                                <select class="font-light form-select" name="tour_style"
                                                    id="tourStyleSelect" multiple required>
                                                    <option value="">Chọn phong cách trải nghiệm</option>
                                                    @foreach ($experientialStyles as $style)
                                                        <option value="{{ $style->id }}">{{ $style->name }}</option>
                                                    @endforeach
                                                </select>

                                                <!-- Custom multi-select display -->
                                                <div class="multi-select-wrapper" id="multiSelectWrapper"
                                                    style="display: none;">
                                                    <div class="multi-select-display" onclick="toggleMultiSelect()"
                                                        tabindex="0">
                                                        <div id="selectedTags" class="d-flex flex-wrap gap-1"></div>
                                                        <div id="placeholderText" class="placeholder-text">Chọn phong cách
                                                            trải nghiệm</div>
                                                        <div class="dropdown-arrow" id="dropdownArrow">▼</div>
                                                    </div>
                                                    <div class="multi-select-options" id="multiSelectOptions"></div>
                                                </div>
                                            </div>

                                            <!-- Participants -->

                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Nhóm khách tối thiểu</label>
                                                <div class="input-group">
                                                    <input type="number" class="font-light form-control"
                                                        name="min_participants" placeholder="1" min="1" required>
                                                    <span class="input-group-text">Khách</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Nhóm khách tối đa</label>
                                                <div class="input-group">
                                                    <input type="number" class="font-light form-control"
                                                        name="max_participants" placeholder="12" min="1" required>
                                                    <span class="input-group-text">Khách</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <!-- Guide Type -->
                                        <!-- Departure Locations -->
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="form-label">Phụ hợp lứa tuổi từ</label>
                                                <div class="input-group">
                                                    <input type="number" class="font-light form-control"
                                                        name="departure_from" placeholder="1" min="1" required>
                                                    <span class="input-group-text">Tuổi</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Phụ hợp lứa tuổi đến</label>
                                                <div class="input-group">
                                                    <input type="number" class="font-light form-control"
                                                        name="departure_to" placeholder="99" min="1" required>
                                                    <span class="input-group-text">Tuổi</span>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-start gap-2 mt-4">
                                    <button type="submit" class="btn save-btn">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.16699 10.0003H15.8337M15.8337 10.0003L10.0003 4.16699M15.8337 10.0003L10.0003 15.8337"
                                                stroke="white" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        Lưu và tiếp tục
                                    </button>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('style_page')
    <style>

    </style>
@endsection

@section('script_page')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tabs
            var triggerTabList = [].slice.call(document.querySelectorAll('#tourTabs button'))
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            });
        });
    </script>
    <script>
        // JavaScript hoàn chỉnh cho Multi-Select
        function toggleMultiSelect() {
            const options = document.getElementById('multiSelectOptions');
            const arrow = document.getElementById('dropdownArrow');

            options.classList.toggle('show');
            arrow.classList.toggle('open');
        }

        function toggleOption(value, text) {
            const originalSelect = document.getElementById('tourStyleSelect');
            const option = originalSelect.querySelector(`option[value="${value}"]`);

            if (option.selected) {
                option.selected = false;
            } else {
                option.selected = true;
            }

            updateDisplay();
        }

        function removeTag(value) {
            const originalSelect = document.getElementById('tourStyleSelect');
            const option = originalSelect.querySelector(`option[value="${value}"]`);
            if (option) {
                option.selected = false;
                updateDisplay();
            }
        }

        function updateDisplay() {
            const originalSelect = document.getElementById('tourStyleSelect');
            const selectedTags = document.getElementById('selectedTags');
            const placeholder = document.getElementById('placeholderText');

            if (!originalSelect || !selectedTags || !placeholder) {
                return;
            }

            const selectedOptions = Array.from(originalSelect.selectedOptions);

            // Xóa tất cả tags hiện tại
            selectedTags.innerHTML = '';

            if (selectedOptions.length === 0 || (selectedOptions.length === 1 && selectedOptions[0].value === '')) {
                placeholder.style.display = 'block';
            } else {
                placeholder.style.display = 'none';

                selectedOptions.forEach(option => {
                    if (option.value !== '') { // Bỏ qua option placeholder
                        const tag = document.createElement('div');
                        tag.className = 'selected-tag';
                        tag.innerHTML = `
                    ${option.textContent}
                    <span class="tag-remove" onclick="removeTag('${option.value}')">×</span>
                `;
                        selectedTags.appendChild(tag);
                    }
                });
            }

            // Cập nhật trạng thái các option trong dropdown
            document.querySelectorAll('.option-item').forEach(optionDiv => {
                const value = optionDiv.getAttribute('data-value');
                const option = originalSelect.querySelector(`option[value="${value}"]`);

                if (option && option.selected) {
                    optionDiv.classList.add('selected');
                } else {
                    optionDiv.classList.remove('selected');
                }
            });
        }

        function initMultiSelect() {
            const originalSelect = document.getElementById('tourStyleSelect');
            const multiSelectWrapper = document.getElementById('multiSelectWrapper');
            const multiSelectOptions = document.getElementById('multiSelectOptions');

            if (!originalSelect || !multiSelectWrapper || !multiSelectOptions) {
                console.error('Không tìm thấy các element cần thiết cho multi-select');
                return;
            }

            // Ẩn select gốc và hiện custom multi-select
            originalSelect.classList.add('multi-select-hidden');
            multiSelectWrapper.style.display = 'block';

            // Tạo các option cho custom multi-select
            const options = originalSelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value !== '') { // Bỏ qua option placeholder
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'option-item';
                    optionDiv.setAttribute('data-value', option.value);
                    optionDiv.textContent = option.textContent;
                    optionDiv.addEventListener('click', function(e) {
                        e.stopPropagation();
                        toggleOption(option.value, option.textContent);
                    });
                    multiSelectOptions.appendChild(optionDiv);
                }
            });

            updateDisplay();
        }

        // Đóng dropdown khi click bên ngoài
        document.addEventListener('click', function(e) {
            const multiSelectWrapper = document.getElementById('multiSelectWrapper');
            if (multiSelectWrapper && !e.target.closest('.multi-select-wrapper')) {
                const options = document.getElementById('multiSelectOptions');
                const arrow = document.getElementById('dropdownArrow');
                if (options) options.classList.remove('show');
                if (arrow) arrow.classList.remove('open');
            }
        });

        // Xử lý phím Enter và Space
        document.addEventListener('keydown', function(e) {
            const multiSelectWrapper = document.getElementById('multiSelectWrapper');
            if (multiSelectWrapper && e.target.closest('.multi-select-display')) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleMultiSelect();
                }
            }
        });

        // Khởi tạo khi DOM đã load
        document.addEventListener('DOMContentLoaded', function() {
            // Delay nhỏ để đảm bảo tất cả element đã được render
            setTimeout(function() {
                initMultiSelect();
            }, 100);
        });

        // Backup: Khởi tạo khi window load (phòng trường hợp DOMContentLoaded không hoạt động)
        window.addEventListener('load', function() {
            if (document.getElementById('tourStyleSelect') && !document.getElementById('tourStyleSelect').classList
                .contains('multi-select-hidden')) {
                initMultiSelect();
            }
        });
    </script>
@endsection
