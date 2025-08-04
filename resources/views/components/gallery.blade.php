<div class="gallery-component">
    <div class="py-3 px-5 d-flex justify-content-center align-items-center flex-column" style="background-color: #f4f4f4; border-radius: 10px">
        <div style="width: 100%" class="mt-3">
            <div class="gallery-component-wrapper" id="input-container-{{ @$name . @$index }}" style="width: 100%">

                @if (isset($dataComponent) && count($dataComponent) > 0)
                    {{-- <hr>
                    <p style="color: #333; font-weight: 400">Tệp đã chọn</p> --}}
                    @foreach ($dataComponent as $item)
                        @php
                            $item = (object) $item;
                        @endphp
                        <div class="gallery-component-item row mb-3">
                            <div class="col-md-2 d-flex justify-content-center">
                                @if (
                                    $item->type == 'jpeg' ||
                                        $item->type == 'png' ||
                                        $item->type == 'jpg' ||
                                        $item->type == 'gif' ||
                                        $item->type == 'webp' ||
                                        $type == 'Images')
                                    <!-- replace with your condition for images -->
                                    <img src="{{ $item->$field }}" alt="{{ $item->field }}"
                                        style="width: 100px; height: 100px; object-fit: cover"
                                        class="img-fluid rounded-2">
                                @elseif($item->type == 'mp4' || $item->type == 'avi' || $item->type == 'mov' || $item->type == 'wmv')
                                    <!-- replace with your condition for videos -->
                                    <video width="100" height="100" controls>
                                        <source src="{{ $item->$field }}" type="video/mp4">
                                        <!-- replace type with the correct video type -->
                                        Your browser does not support the video tag.
                                    </video>
                                @endif

                                <input form="formAccountSettings" class="form-control" id="input-{{ @$name }}-{{ $item->id }}" name="{{ @$name }}[image][{{ @$index }}][]" type="hidden" value="{{ $item->$field }}" />
                                <input form="formAccountSettings" type="hidden" id="input-ordering-{{ $item->id }}" name="{{ $name }}[ordering][{{ @$index }}][]" class="form-control" value="{{ $item->ordering }}" />
                                <input form="formAccountSettings" type="hidden" name="{{ @$name }}[type][{{ @$index }}][]" value="{{ $item->type }}" />
                            </div>
                            <div class="d-flex col-md-10 align-items-center">
                                {{ @$slot }} 
                            </div>
                            <div>
                                <a class="delete-icon"><i class="ms-3 menu-icon tf-icons mdi mdi-delete-circle" style="color: red"></i></a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center" style="color: #333; font-weight: 400">Chưa có dữ liệu</p>
                @endif
            </div>
        </div>

        <div class="d-flex">
            <button type="button" class="btn btn-primary waves-effect waves-light openPopUp" onclick="openPopupMulti{{ @$name . @$index }}('{{ $name }}', '{{ @$index }}')">Chọn tệp</button>
        </div>
    </div>
</div>

{{-- @push('push_script') --}}
<script>
    function checkEmpty() {
        if ($('#inputContainer').children().length === 0) {
            var newInput = `
                <div class="d-flex w-100 align-items-center">
                    <i class="mdi mdi-arrow-right"></i>
                <div class="form-floating form-floating-outline mt-3 ms-5 w-100">
                    <input type="text" class="form-control" name="content[]" id="floatingInput" placeholder="Nội dung"
                        aria-describedby="floatingInputHelp" value="">
                    <label for="floatingInput">Nội dung</label>
                </div>
                <a class="delete-icon"><i class="ms-3 menu-icon tf-icons mdi mdi-delete-circle" style="color: red"></i></a>
            </div>
        `;
            $('#inputContainer').append(newInput);
        }
    }

    function setSort() {
        Sortable.create(document.querySelector(
        '#input-container-{{ @$name . @$index }}'), {
            onChange: function(evt) {
                evt.target.querySelectorAll(`input[name^="${name}[ordering"]`).forEach((input, indexOrdering) => {
                    input.value = indexOrdering 
                })
            }
        })
    } 

    function openPopupMulti{{ @$name . @$index }}(name, index) {
        CKFinder.popup({
            chooseFiles: true,
            chooseFilesMultiple: true,
            resourceType: '{{ $type }}' ? '{{ $type }}' : 'Images',
            onInit: function(finder) {
                finder.on('files:choose', function(evt) {
                    var files = evt.data.files
                    var container = document.getElementById('input-container-{{ @$name . @$index }}')
                    container.classList.add('gallery-component-wrapper')

                    setSort()

                    // Xóa các input cũ
                    while (container.firstChild) {
                        container.firstChild.remove()
                    }
                 
                    let ordering = 0;
                    files.forEach(function(file) {
                        var url = file.attributes.url
                        var parts = url.split('.')
                        var extension = parts[parts.length - 1]
 
                        var input = `
                            <div class="gallery-component-item row mb-3">
                                <div class="col-md-2">
                                    <img src="${file.getUrl()}" alt="Image" style="width: 100px; height: 100px; object-fit: cover" class="img-fluid rounded-2">
                                    <input form="formAccountSettings" type="hidden" id="input-ckfinder-${name}${index}" name="${name}[image][${index}][]" class="form-control" value="${file.getUrl()}" readonly />
                                    <input form="formAccountSettings" type="hidden" id="input-ordering-ckfinder-${name}${index}" name="${name}[ordering][${index}][]" class="form-control" value="${ordering}" />
                                    <input form="formAccountSettings" type="hidden" name="${name}[type][${index}][]" value="${extension}" />
                                </div>
                                <div class="d-flex col-md-10 align-items-center">
                                    {{ @$slot }} 
                                </div>
                                <div> 
                                    <a class="delete-icon"><i class="ms-3 menu-icon tf-icons mdi mdi-delete-circle" style="color: red"></i></a>
                                </div>
                            </div>
                        `;
                        $('#input-container-{{ @$name . @$index }}').append(input)
                        ordering ++
                    })
                })
            }
        })
    }
</script>
{{-- @endpush --}}

@push('push_script')
    <script>
        Sortable.create(document.querySelector('#input-container-{{ @$name . @$index }}'), {
            onChange: function(evt) {
                evt.target.querySelectorAll(`input[name^="${name}[ordering"]`).forEach((input, indexOrdering) => {
                    input.value = indexOrdering 
                })
            }
        })
        
        $(document).on('click', '.delete-icon', function() {
            $(this).closest('.gallery-component-item').remove()
            // checkEmpty()
        });
    </script>
@endpush
