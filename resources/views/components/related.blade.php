<div>

    
    <p class="fw-medium">
        {{ $title }}
    </p>

    <a href="" class="btn btn-primary me-2 waves-effect waves-light" data-bs-toggle="modal"
        data-bs-target="#{{ $name }}-modal">
        Thêm
    </a>

    <table class="table table-hover {{ $name }}-table mt-3">
        <thead>
            <tr>
                <th>Tên</th>
                @if (!empty($dataTable))
                    @foreach ($dataTable as $key => $item)
                        <th>{{ $item['title'] }}</th>
                    @endforeach
                @endif
                <th>Control</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($dataComponent))
                <input type="hidden" form="formAccountSettings" name="{{ $name }}[remove]" value="">
                @foreach ($dataComponent as $item)
                    <tr>
                        <td>
                            <input form="formAccountSettings" type="hidden" name="{{ $name }}[id][]" class="form-control" value="{{ $item->id }}" />
                            <div class="d-flex gap-2">
                                <img width="60" height="60" src="{{ $item->image }}" alt="" class="img-fluid rounded-2" />
                                <div>
                                    <div>{{ $item->name }}</div>
                                </div>
                            </div>
                        </td>
                        @if (!empty($dataTable))
                            @foreach ($dataTable as $itemName => $itemInfo)
                                @php
                                    if ($itemName == 'price_old' || $itemName == 'price') {
                                        $value = @$item->pivot->$itemName ? number_format(@$item->pivot->$itemName, 0, ',', '.') . ' ₫' : '';
                                    } else {
                                        $value = @$item->pivot->$itemName;
                                    }
                                @endphp
                                <td>
                                    @if (isset($itemInfo['type']))
                                        <input form="formAccountSettings" type="{{ $itemInfo['type'] }}" class="form-control" name="{{ $name }}[{{ $itemName }}][]"
                                            value="{{ @$value }}" {{ @$itemInfo['readonly'] ? 'readonly' : '' }} />
                                    @else
                                        {{ @$item->$itemName }}
                                    @endif
                                </td>
                            @endforeach
                        @endif
                        <td>
                            <a href="" class="{{ $name }}-remove" data-id="{{ $item->id }}">Xóa</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="modal fade" id="{{ $name }}-modal" tabindex="-1" aria-labelledby="{{ $name }}-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $name }}-modalLabel">{{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-2 mb-3">
                        <select name="{{ $name }}-cat" id="{{ $name }}-cat" class="form-select form-select-sm">
                            <option value="0">Danh mục</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">
                                    {!! $item->treename !!}
                                </option>
                            @endforeach
                        </select>

                        <input type="text" class="form-control" id="{{ $name }}-keyword" name="{{ $name }}-keyword"
                            placeholder="Tìm kiếm" required="" autocomplete="off">

                        <button class="btn btn-primary fw-semibold text-nowrap" type="submit" id="{{ $name }}-search">Tìm kiếm</button>
                    </div>

                    <div class="table-container mb-3 overflow-auto" style="height: 60vh;">
                        <table class="table table-hover table-result {{ $name }}-modal-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="{{ $name }}-checkall">
                                    </th>
                                    <th>Tên</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-3 flex-wrap">
                        <button id="{{ $name }}-cancel" class="btn btn-secondary">Hủy</button>
                        <button disabled id="{{ $name }}-add" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style lang="scss">
        #{{ $name }}-modal .select2{
            width: 200px !important;
        } 
    </style>
</div>


@push('pricing-script')
    <script>
        const {{ $name }}_max_item = 0
        let {{ $name }}_remove = []
        let {{ $name }}_id = [{{ !empty($dataComponent) ? $dataComponent->pluck('id')->implode(',') : '' }}]

        $(document).ready(function() {
            $('#{{ $name }}-cat').select2({
                dropdownParent: $("#{{ $name }}-modal")
            })
        })
      
        // checked items
        $('#{{ $name }}-checkall').change(function() {
            if ($(this).is(':checked')) {
                $("#{{ $name }}-modal table tbody input[type=checkbox]").prop('checked', true)
            } else {
                $("#{{ $name }}-modal table tbody input[type=checkbox]").prop('checked', false)
            }

            {{ $name }}_check()
        })

        $(document).on('change', "#{{ $name }}-modal table tbody input[type=checkbox]", function(){
            {{ $name }}_check()
        })

        function {{ $name }}_check() {
            if ($("#{{ $name }}-modal table tbody input[type=checkbox]:checked").length) {
                $('#{{ $name }}-add').attr('disabled', false)
            } else {
                $('#{{ $name }}-add').attr('disabled', true)
            }
        }


        // cancel - remove items checked
        $('#{{ $name }}-cancel').click(function() {
            $('#{{ $name }}-add').attr('disabled', true)
            $("#{{ $name }}-modal table tbody input[type=checkbox]").prop('checked', false)
            $("#{{ $name }}-checkall").prop('checked', false)
            $('#{{ $name }}-modal table tbody').empty()

            $("#{{ $name }}-modal").modal('hide')
        })


        // add items
        $('#{{ $name }}-add').click(function() {
            let html
            let totalCurrent = $('.{{ $name }}-table tbody tr').length 

            $("#{{ $name }}-modal table tbody input[type=checkbox]:checked").each(function(i, item){
                let data = $(this).data('data')

                if (!{{ $name }}_id.includes(parseInt(data.id)) && ({{ $name }}_max_item <= 0 || totalCurrent < {{ $name }}_max_item)) {
                    html += `
                        <tr>
                            <td>
                                <input form="formAccountSettings" type="hidden" name="{{ $name }}[id][]" class="form-control" value="${data.id}" />
                                <div class="d-flex gap-2">
                                    <img width="60" height="60" src="${data.image}" alt="" class="img-fluid rounded-2" />
                                    <div>
                                        <div>${data.name}</div> 
                                    </div>
                                </div>
                            </td>
                            @if (!empty($dataTable))
                                @foreach ($dataTable as $itemName => $itemInfo)
                                    <td>
                                        @if (isset($itemInfo['type']))
                                            <input form="formAccountSettings" type="{{ $itemInfo['type'] }}" class="form-control" name="{{ $name }}[{{ $itemName }}][]"
                                                value="" {{ @$itemInfo['readonly'] ? 'readonly' : '' }} />
                                        @else
                                            ${data.{{ $itemName }}}
                                        @endif
                                    </td>
                                @endforeach
                            @endif
                            <td>
                                <a href="" class="{{ $name }}-remove" data-id="${data.id}">Xóa</a>
                            </td>
                        </tr>
                    `
                    {{ $name }}_id.push(parseInt(data.id));
                    totalCurrent ++;
                }
            })

            $('.{{ $name }}-table tbody').append(html)
            $('#{{ $name }}-modal table tbody').empty()
            $('#{{ $name }}-modal').modal('hide') 
            $('.select2-temp').select2()
        })


        // remove items
        $(document).on('click', '.{{ $name }}-remove', function(e){
            e.preventDefault()
            let id = parseInt($(this).attr('data-id'))
            $(this).closest('tr').remove()

            let index = {{ $name }}_id.indexOf(id);
    
            if (index !== -1) {
                {{ $name }}_remove.push(id)
                $(`input[name="{{ $name }}[remove]"]`).val({{ $name }}_remove)
                {{ $name }}_id.splice(index, 1);
            }
        })


        // search items
        $('#{{ $name }}-modal').on('shown.bs.modal', function(e) {
            {{ $name }}_searchModal()
        })

        $('#{{ $name }}-search').click(function() {
            {{ $name }}_searchModal()
        })

        function {{ $name }}_searchModal() {
            let category_id = $('#{{ $name }}-cat').val()
            let keyword = $('#{{ $name }}-keyword').val()

            $('#{{ $name }}-modal table tbody').empty().append('<tr><td colspan="4" class="text-center">Loading...</td></tr>')

            $.ajax({
                url: "{{ $routeAjax }}",
                type: 'POST',
                data: {
                    category_id,
                    keyword
                },
                dataType: 'JSON',
                success: function(result) {
                    $('#{{ $name }}-modal table tbody').empty()

                    result.data.forEach(function(item) {
                        let html
                        let checked = ''
                        if ({{ $name }}_id.includes(parseInt(item.id))) {
                            checked = 'checked'
                        }
                        
                        html += `
                            <tr class="${checked}">
                                <td>
                                    <input id="modal-product-${item.id}" type="checkbox" class="search-check" ${checked} value="${item.id}"  />
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <img width="40" height="40" src="${item.image}" class="img-fluid" />
                                        <div>
                                            <div class="mb-1">${item.name}</div>
                                        </div>
                                    </div>
                                </td> 
                            </tr>
                        `
                        $('#{{ $name }}-modal table tbody').append(html)
                        $(`#modal-product-${item.id}`).data('data', item)
                    })
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    let message =
                        'Có lỗi trong quá trình đưa lên máy chủ. Xin bạn vui lòng kiểm tra lỗi kết nối.'
                    $('#{{ $name }}-modal table tbody').empty().append('<tr><td colspan="4" class="text-center">' +
                        message + '</td></tr>')
                }
            })
        }
    </script>
@endpush