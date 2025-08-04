<div class="card-body">
    <div>
        <label for="formFile" class="form-label">File đã chọn</label>
        @if (@$dataComponent)
            @foreach ($dataComponent as $item)
                <div class="d-flex align-items-center mb-3">
                    @if ($item->type == 'jpeg' || $item->type == 'png' || $item->type == 'jpg' || $item->type == 'gif')
                        <!-- replace with your condition for images -->
                        <img src="{{ $item->url }}" alt="Image"
                            style="width: 320px; height: 100px; object-fit: contain">
                    @elseif($item->type == 'mp4' || $item->type == 'avi' || $item->type == 'mov' || $item->type == 'wmv')
                        <!-- replace with your condition for videos -->
                        <video width="320" height="240" controls>
                            <source src="{{ $item->url }}" type="video/mp4">
                            <!-- replace type with the correct video type -->
                            Your browser does not support the video tag.
                        </video>
                    @endif
                    <div class="form-floating form-floating-outline">
                        <input form="formAccountSettings" class="form-control ms-4" id="input-{{$name}}-{{ $item->id }}"
                         name="input-{{$name}}[]"   type="text" value="{{ $item->url }}" style="width: 80%">
                        <label for="input-{{ $item->id }}">Url</label>
                    </div>
                    <div class="form-floating form-floating-outline ms-3">
                        <input form="formAccountSettings" type="text" id="input-ordering-{{ $item->id }}"
                            name="ordering-{{ $name }}[]" class="form-control" value="{{ $item->ordering }}" />
                        <label for="input-ordering-{{ $item->id }}">Số thứ tự</label>
                    </div>
                    <input form="formAccountSettings" type="hidden" value="{{$item->id}}" name="input-id-{{$name}}[]" />
                    <input form="formAccountSettings" type="hidden" name="type-{{ $name }}[]" value="{{$item->type}}" />
                    <a data-id-image={{ $item->id }} class="delete-icon-ajax">
                        <i class="ms-3 menu-icon tf-icons mdi mdi-delete-circle" style="color: red"></i>
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-center">Chưa có dữ liệu</p>
        @endif
    </div>
    <div class="mb-3">
        <label for="formFile" class="form-label">Chọn file</label>
        <div class="d-flex">
            <button type="button" class="btn btn-info waves-effect waves-light openPopUp"
                onclick="openPopupMulti{{ $name }}('{{ $name }}')">Chọn
                file</button>
        </div>
    </div>
    <div id="input-container-{{ $name }}">

    </div>
</div>

<script>
    function openPopupMulti{{ $name }}(name) {
        CKFinder.popup({
            chooseFiles: true,
            resourceType: '{{ $type }}' ? '{{ $type }}' : '',
            onInit: function(finder) {
                finder.on('files:choose', function(evt) {
                    var files = evt.data.files;
                    var container = document.getElementById('input-container-{{ $name }}');

                    // Xóa các input cũ
                    while (container.firstChild) {
                        container.firstChild.remove();
                    }

                    // Tạo mới input cho mỗi file được chọn
                    var count = 0;
                    files.forEach(function(file) {
                        var url = file.attributes.url;
                        var parts = url.split('.');
                        var extension = parts[parts.length - 1];

                        count++;
                        var input = `<div class="d-flex align-items-center mb-3">
                            <div class="form-floating form-floating-outline">
                            <input form="formAccountSettings" type="text" id="input-ckfinder-${count}" name="${name}[]" class="form-control" value="${file.getUrl()}" />
                            <label for="input-ckfinder-${count}">Url</label>
                            </div>
                            <div class="form-floating form-floating-outline ms-3">
                                <input form="formAccountSettings" type="text" id="input-ordering-ckfinder-${count}" name="ordering-${name}[]" class="form-control" value="${count}" />
                                <label for="input-ckfinder-${count}">Số thứ tự</label>
                            </div>
                            <input form="formAccountSettings" type="hidden" name="type-{{ $name }}[]" value="${extension}" />
                            <a class="delete-icon"><i class="ms-3 menu-icon tf-icons mdi mdi-delete-circle" style="color: red"></i></a>
                            </div>`;
                        $('#input-container-{{ $name }}').append(input);
                    });
                });
            }
        });
    }
</script>
