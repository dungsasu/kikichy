@extends('admin.contentNavLayout')

@section('title', 'Ngôn ngữ')

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection


@section('content')
    <div class="d-flex justify-content-between action-save">
        <h4 class="py-3 mb-0 d-flex align-items-center">
            <span class="text-muted fw-light ps-3"></span> Bản dịch ngôn ngữ
        </h4>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <span>Nhập từ khoá tương ứng</span>
        </div>
        <div class="card-body">
            <div class="wrapper-box">
                @foreach ($list as $key => $item)
                    <div class="row mb-3" data-row="{{ $key }}">
                        <div class="col-md-5">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" data-original-value={{ @$item->key }}
                                    name="key" value="{{ @$item->key }}" placeholder="">
                                <label for="firstName">Key</label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class=" form-floating form-floating-outline">
                                <input class="form-control" type="text" name="text"
                                    data-original-value={{ @$item->text }} value="{{ @$item->text }}" placeholder="">
                                <label for="firstName">EN</label>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <span class="d-flex">
                                <a style="width: 30px" class="dropdown-item p-1">
                                    <i class="mdi mdi-content-save me-1"></i>
                                </a>
                                <a style="width: 30px" class="dropdown-item p-1 delete-record-translation">
                                    <i class="mdi mdi-trash-can-outline me-1"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" id="addRowButton" class="btn btn-outline-primary me-2 waves-effect waves-light mt-3">Thêm từ khoá</button>
        </div>

        {{ $list->links() }}
    </div>
@endsection


@section('script_page')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.mdi-content-save', function(e) {
                e.preventDefault();
                var row = $(this).closest('.row');
                var key = row.find('input[name^="key"]').val();
                var text = row.find('input[name^="text"]').val();
                var data = {
                    key: key,
                    text: text
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.save-translation') }}",
                    data: data,
                    success: function(response) {
                        showToasts(response.message, 'success');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving data:", error);
                    }
                });
            });

            $(document).on('click', '.delete-record-translation', function(e) {
                e.preventDefault();
                var row = $(this).closest('.row');

                var key = row.find('input[name^="key"]').val();
                var text = row.find('input[name^="text"]').val();

                if (key === '' && text === '') {
                    row.remove();
                    return;
                }
                var data = {
                    key: key,
                    text: text
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.delete-translation') }}",
                    data: data,
                    success: function(response) {
                        showToasts(response.message, 'success');
                        row.remove();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving data:", error);
                    }
                });
            });

            function appendNewRow() {
                var newRow = $('.row:first').clone();
                newRow.find('input').val('');
                var rowCount = $('.wrapper-box .row').length;
                newRow.attr('data-row', rowCount).find('input[name="key[]"]').attr('name', 'key');
                newRow.find('input[name="text[]"]').attr('name', 'text');
                $(".wrapper-box").append(newRow)
            }

            $('#addRowButton').click(function() {
                var rowCount = $('.wrapper-box .row').length - 1;
                var lastRow = $('.row[data-row="' + rowCount + '"]');
                var key = lastRow.find('input[name^="key"]').val();
                var text = lastRow.find('input[name^="text"]').val();

                var originalKey = lastRow.find('input[name^="key"]').data('original-value');
                var originalText = lastRow.find('input[name^="text"]').data('original-value');

                if (!key.trim()) {
                    showToasts('Key không được bỏ trống', 'error');
                    return;
                }


                var data = {
                    key: key,
                    text: text
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.save-translation') }}",
                    data: data,
                    success: function(response) {
                        appendNewRow(); // Chỉ thêm hàng mới sau khi lưu thành công
                        showToasts('Thành công', 'success');
                        // Cập nhật giá trị ban đầu sau khi lưu thành công
                        lastRow.find('input[name^="key"]').data('original-value', key);
                        lastRow.find('input[name^="text"]').data('original-value', text);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving data:", error);
                    }
                });
            });
        });
    </script>

@endsection
