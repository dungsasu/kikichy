<div class="col-md-12">
    <table class="table table-hover table-attributes  ">
        <thead>
            <th>Tên</th> 
            <th>Mã/ SKU</th>
            <th>Giá bán</th>
            <th>Giá gốc</th>
            <th style="width: 150px;">Tồn kho</th> 
            <th>Kích hoạt</th>
            <th>Control</th>
        </thead>
        <tbody>
            @if(!empty(@$attributes))
                <input type="hidden" form="formAccountSettings" name="attributes[remove]" value="">
                @foreach ($attributes as $i => $item)
                    <tr class="newRow newRow{{ $i }}">
                        <td>
                            <input type="hidden" form="formAccountSettings" name="attributes[id][]" value="{{ $item->id }}" />
                            <div class="d-flex gap-2">
                                <input type="text" form="formAccountSettings" class="form-control" placeholder="Tên phân loại" name="attributes[name][]" value="{{ $item->name }}" />
                                <input type="color" form="formAccountSettings" class="form-control form-control-color" value="{{ $item->color_code }}"  name="attributes[color_code][]" title="Choose your color">
                            </div>
                        </td>
                        <td>
                            <input type="text" form="formAccountSettings" class="form-control" placeholder="Mã sản phẩm" name="attributes[code][]" value="{{ $item->code }}" />
                        </td>
                        <td>
                            <input type="text" form="formAccountSettings" class="form-control" placeholder="Giá bán" name="attributes[price][]" value="{{ format_money($item->price) }}" />
                        </td>
                        <td>
                            <input type="text" form="formAccountSettings" class="form-control" placeholder="Giá gốc" name="attributes[price_old][]" value="{{ format_money($item->price_old) }}" />
                        </td>
                        <td>
                            <input type="text" form="formAccountSettings" class="form-control" placeholder="Tồn kho" name="attributes[quantity][]" value="{{ $item->quantity }}" readonly disabled />
                        </td> 
                        <td>
                            <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="attributes[published][]" {{ $item->published ? 'checked' : '' }}>
                        </td>
                        <td>
                            <a href="javscript:void(0)" class="removeAttributes" data-id="{{ $item->id }}">
                                Xóa
                            </a>
                        </td> 
                    </tr>
                    <tr class="newRowImage newRowImage{{ $i }}">
                        <td colspan="7">
                            <div class="fw-medium">Ảnh</div>
    
                            <x-gallery name="attributes" index="{{ $i }}" :data-component="$item->images" field="image" type="Images" />
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="newRow newRow0">
                    <td>
                        <input type="text" form="formAccountSettings" class="form-control" placeholder="Tên phân loại" name="attributes[name][]" value="" />
                    </td>
                    <td>
                        <input type="text" form="formAccountSettings" class="form-control" placeholder="Mã sản phẩm" name="attributes[code][]" value="" />
                    </td>
                    <td>
                        <input type="text" form="formAccountSettings" class="form-control" placeholder="Giá bán" name="attributes[price][]" value="" />
                    </td>
                    <td>
                        <input type="text" form="formAccountSettings" class="form-control" placeholder="Giá gốc" name="attributes[price_old][]" value="" />
                    </td>
                    <td>
                        {{-- <input type="text" form="formAccountSettings" class="form-control" placeholder="Tồn kho" name="attributes[quantity][]" value="0" readonly /> --}}
                    </td> 
                    <td>
                        <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="attributes[published][]" checked>
                    </td>
                    <td>
                        <a href="javscript:void(0)" class="removeAttributes" data-id="0">
                            Xóa
                        </a>
                    </td> 
                </tr>
                <tr class="newRowImage newRowImage0">
                    <td colspan="7">
                        <div class="fw-medium">Ảnh</div>

                        <x-gallery name="attributes" index="0" :data-component="[]" field="image" type="Images" />
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="javascript:void(0)" id="addAttributes">Thêm phân loại</a>
    </div>

</div>

@push('push_style')
    <style>
        
    </style>
@endpush
@push('push_script')
    <script>
        let exist = {{ !empty(@$attributes) ? count($attributes) : 1 }}
        let remove = []

        const newRow = `
            <tr class="newRow newRow${exist}">
                <td>
                    <div class="d-flex gap-2">
                        <input type="text" form="formAccountSettings" class="form-control" placeholder="Tên phân loại" name="attributes[name][]" value="" />
                        <input type="color" form="formAccountSettings" class="form-control form-control-color" value="" name="attributes[color_code][]">
                    </div>
                </td>
                <td>
                    <input type="text" form="formAccountSettings" class="form-control" placeholder="Mã sản phẩm" name="attributes[code][]" value="" />
                </td>
                <td>
                    <input type="text" form="formAccountSettings" class="form-control" placeholder="Giá bán" name="attributes[price][]" value="" />
                </td>
                <td>
                    <input type="text" form="formAccountSettings" class="form-control" placeholder="Giá gốc" name="attributes[price_old][]" value="" />
                </td>
                <td>
                    <!-- <input type="text" form="formAccountSettings" class="form-control" placeholder="Tồn kho" name="attributes[quantity][]" value="0" readonly disabled /> -->
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="attributes[published][]" checked>
                </td>
                <td>
                    <a href="javscript:void(0)" class="removeAttributes" data-id="0">
                        Xóa
                    </a>
                </td> 
            </tr>
        `

        $('#addAttributes').click(function(e){
            e.preventDefault() 
            let newRowImage = newRow

            $.ajax({
                url: '/api/get-gallery-component/' + exist +'?name=attributes',
                type: 'GET',
                success: function(response) {  
                    newRowImage += `<tr class="newRowImage newRowImage${exist}" ><td colspan="7"><div class="fw-medium">Ảnh</div>`
                    newRowImage += response.html
                    newRowImage += `</td></tr>` 
                    $('.table-attributes tbody').append(newRowImage) 
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching component:', error)
                }
            })

            exist++
        })

        $(document).on('click', '.removeAttributes', function(e){
            e.preventDefault()
            let id = $(this).data('id')

            let row = $(this).closest(`.newRow`)
            row.next(`.newRowImage`).remove()
            row.remove()

            if (id != 0) {
                remove.push(id)
                $(`input[name="attributes[remove]"]`).val(remove)
            }        
             
        })
    </script>
@endpush
