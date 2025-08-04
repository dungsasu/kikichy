<div class="mb-4">
    <label for="formFile" class="form-label fw-semibold mb-3">{{ @$title }}</label>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-column align-items-center">
                <!-- Preview Section -->
                <div class="preview-wrapper mb-3" style="max-width: 300px; width: 100%;">
                    @if (strtolower(@$dataComponent->type) == 'jpeg' ||
                            strtolower(@$dataComponent->type) == 'png' ||
                            strtolower(@$dataComponent->type) == 'jpg' ||
                            strtolower(@$dataComponent->type) == 'gif' ||
                            strtolower(@$dataComponent->type) == 'svg' ||
                            strtolower(@$dataComponent->type) == 'webp' ||
                            $type == 'Images')
                        <img class="img-fluid rounded border" src="{{ @$dataComponent->$field }}" alt="Image"
                            id="image-choosefile-{{ $id }}"
                            style="width: 100%; height: 160px; object-fit: contain; background-color: #f8f9fa;"
                            onerror="this.src='/img/no-image.png'">
                    @elseif(
                        @$dataComponent->type == 'mp4' ||
                            @$dataComponent->type == 'avi' ||
                            @$dataComponent->type == 'mov' ||
                            @$dataComponent->type == 'wmv')
                        <video class="w-100 rounded border" height="160" controls>
                            <source src="{{ @$dataComponent->$field }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded border"
                            style="height: 160px;">
                            <i class="fas fa-file-alt fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>

                <!-- File Path -->
                <div class="file-path-wrapper w-100" style="max-width: 300px;">
                    <p class="small text-muted mb-2">
                        <i class="fas fa-link me-1"></i>Đường dẫn:
                    </p>
                    <p class="text-truncate bg-light p-2 rounded small border mb-3"
                        style="font-family: monospace; word-break: break-all; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                        title="{{ @$dataComponent->$field }}">
                        {{ @$dataComponent->$field ?: 'Chưa chọn tệp' }}
                    </p>
                    <!-- Hidden input -->
                    <input type="hidden" name="{{ $id }}" id="{{ $id }}"
                        value="{{ @$dataComponent->$field }}" class="{{ @$class }}">
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-primary btn-sm" title="Chọn tệp"
                        onclick="openPopup{{ $id }}('multiFileCustomer')">
                        <i class="fas fa-upload"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" title="Xóa"
                        onclick="$('#{{ $id }}').val(''); $(`#image-choosefile-{{ $id }}`).attr('src', '/img/no-image.png'); $(this).closest('.card-body').find('.text-truncate').text('Chưa chọn tệp').attr('title', '');">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($type !== 'Images')
        <input type="hidden" name="type" id="type-1" value="{{ @$dataComponent->type }}">
    @endif
</div>

@push('push_script')
    <script>
        function openPopup{{ $id }}() {
            CKFinder.popup({
                chooseFiles: true,
                resourceType: '{{ $type }}' ? '{{ $type }}' : '',
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        var url = file.attributes.url;
                        var parts = url.split('.');
                        var extension = parts[parts.length - 1];

                        document.getElementById('{{ $id }}').value = file.getUrl();
                        if (document.getElementById('type-1')) {
                            document.getElementById('type-1').value = extension;
                        }
                        $(`#image-choosefile-{{ $id }}`).attr('src', file.getUrl());

                        // Update path display
                        $(document).find('#{{ $id }}').closest('.card-body').find(
                            '.text-truncate').text(file.getUrl()).attr('title', file.getUrl());
                    });
                    finder.on('file:choose:resizedImage', function(evt) {
                        document.getElementById('{{ $id }}').value = evt.data.resizedUrl;
                        $(document).find('#{{ $id }}').closest('.card-body').find(
                            '.text-truncate').text(evt.data.resizedUrl).attr('title', evt.data
                            .resizedUrl);
                    });
                }
            });
        }
        $('#{{ $id }}').on('input', function() {
            var url = $(this).val();
            var type = '';

            if (url.indexOf('youtube') !== -1) {
                type = 'youtube';
            } else if (url.endsWith('.jpg')) {
                type = 'jpg';
            }

            $("#type-1").val(type);
        });
    </script>
@endpush
