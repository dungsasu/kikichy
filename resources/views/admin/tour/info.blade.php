<form action={{ route($view . '.save') }} id="formAccountSettings" method="POST" enctype="multipart/form-data">
     
    <div class="pt-2 mt-1 mb-4">
        <!-- Account -->
         
        @csrf
       
        <input type="hidden" name="info[id]" value="{{ @$data->id }}">
        @if (@$data->id)
            {{-- <a target="_blank"
                href="{{ route('client.tour', ['category' => @$data->category->alias ? @$data->category->alias : 1, 'alias' => @$data->alias]) }}"> --}}
            <a target="_blank"
                href="">
                <i class="mdi mdi-earth"></i>
                Xem sản phẩm
            </a>
        @endif

        <div class="row mt-2 gy-4">
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="name" name="info[name]"
                        value="{{ old('info.name', @$data->name) }}" placeholder="" />
                    <label for="firstName">Tên</label>
                </div>
            </div>

            <div class="col-md-6">
                <div>
                    @if (isset($categories) && count($categories) > 0)
                        <select name="info[category_id]" class="form-select select2 form-select-sm">
                            <option value="0">---Danh mục---</option>
                            @foreach ($categories as $category)
                                @if (@$data->category_id == $category->id && @$data->id)
                                    <option
                                        {{ @$data->category_id == $category->id && @$data->id ? 'selected' : null }}
                                        value="{{ $category->id }}">{!! $category->treename !!}</option>
                                @else
                                    <option value="{{ $category->id }}">{!! $category->treename !!}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" name="info[alias]" id="alias"
                        value="{{ @$data->alias }}" placeholder="" />
                    <label for="lastName">Alias</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="version" name="info[version]"
                        value="{{ @$data->version }}" placeholder="256G" />
                    <label for="version">Tên phiên bản</label>
                </div>
            </div>

            <div class="col-md-6 d-flex gap-4">
                <div class="form-floating form-floating-outline w-100 ">
                    <input class="form-control" type="text" id="ordering" name="info[ordering]"
                        value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}" placeholder="" />
                    <label for="ordering">Thứ tự</label>
                </div>
                <div class="form-floating form-floating-outline w-100">
                    <select name="info[status]" class="form-select form-control">
                        <option value="0" disabled >---Trạng thái---</option>
                        <option value="1" {{ @$data->status == 1 ? 'selected' : null }}>Đang kinh doanh</option>
                        <option value="2" {{ @$data->status == 2 ? 'selected' : null }}>Chờ hàng</option>
                    </select>
                    <label for="version">Trạng thái</label>
                </div>
            </div> 
           

            <div class="col-md-6">
                <div class="d-flex flex-wrap gap-3 align-items-center h-100">
                    <div class="form-check form-switch mb-0">
                        <label for="published">Kích hoạt</label>
                        <input form="formAccountSettings" id="published"
                            class="form-check-input float-start" type="checkbox" value="1"
                            name="info[published]" role="switch"
                            {{ @$data->published || !@$data->id ? 'checked' : null }}>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <label for="hot">Hot</label>
                        <input form="formAccountSettings" id="hot"
                            class="form-check-input float-start" type="checkbox" value="1"
                            name="info[hot]" role="switch"
                            {{ @$data->hot || !@$data->id ? 'checked' : null }}>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <label for="new">New</label>
                        <input form="formAccountSettings" id="new"
                            class="form-check-input float-start" type="checkbox" value="1"
                            name="info[new]" role="switch"
                            {{ @$data->new || !@$data->id ? 'checked' : null }}>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <label for="gmc">Google Merchant</label>
                        <input form="formAccountSettings" id="gmc"
                            class="form-check-input float-start" type="checkbox" value="1"
                            name="info[gmc]" role="switch"
                            {{ @$data->gmc || !@$data->id ? 'checked' : null }}>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex gap-4">
                <div class="form-floating form-floating-outline w-100 ">
                    <input class="form-control" type="text" id="version_text" name="info[version_text]"
                        value="{{ @$data->version_text }}" placeholder="Dung lượng" />
                    <label for="version_text">Tên loại phiên bản</label>
                </div>
                <div class="form-floating form-floating-outline w-100 ">
                    <input class="form-control" type="text" id="attribute_text" name="info[attribute_text]"
                        value="{{ @$data->attribute_text }}" placeholder="Màu sắc" />
                    <label for="attribute_text">Tên loại phân loại</label>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-floating form-floating-outline w-100 ">
                    <input class="form-control" type="text" id="brand" name="info[brand]"
                        value="{{ @$data->brand }}" placeholder="Thương hiệu" />
                    <label for="brand">Thương hiệu</label>
                </div>
            </div>
            
            <div class="col-md-4">
                <x-choose-file title="Ảnh đại diện" :type="'Images'" id="image"
                    name="info[image]" :dataComponent="@$data" field="image" />
            </div>
            <div class="col-md-4">
                <x-choose-file title="Video" :type="'Videos'" id="video"
                    name="info[video]" :dataComponent="@$data" field="video" readonly="false" />
            </div>
            <div class="col-md-4">
                <x-choose-file title="Ảnh trong hộp" :type="'Images'" id="image_box"
                    name="info[image_box]" :dataComponent="@$data" field="image_box" />
            </div>
            <div class="col-md-12">
                <label for="formFile">Ảnh đặc điểm nổi bật</label>
                {{-- <x-choose-file title="Đặc điểm nổi bật" :type="'Images'" id="image_feature"
                    name="info[image_feature]" :dataComponent="@$data" field="image_feature" /> --}}
                <x-gallery name="images" index="0" :data-component="@$data->images" field="image" type="Images" />
            </div>

            
            <div class="col-md-12"> 
                <x-editor_v2 name="info[guarantee]" id="guarantee" title="Chính sách bảo hành"
                    content="{{ @$data->guarantee }}" /> 
            </div>

            <div class="col-md-12"> 
                <x-editor_v2 name="info[sale_brief]" id="sale_brief" title="Khuyến mại"
                    content="{{ @$data->sale_brief }}" /> 
            </div>

            <div class="col-md-12"> 
                <x-editor_v2 name="info[description]" id="description" title="Thông tin sản phẩm"
                    content="{{ @$data->description }}" /> 
            </div>

            {{-- <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="price" name="price"
                        value="{{ @$data->price ? @$data->price : 0 }}" placeholder="" />
                    <label for="price">Giá bán</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="price_old" name="price_old"
                        value="{{ @$data->price_old ? @$data->price_old : 0 }}" placeholder="" />
                    <label for="price_old">Giá gốc</label>
                </div>
            </div> --}}
            {{-- <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text" id="code" name="code"
                        value="{{ @$data->code ? @$data->code : @$code }}" placeholder="" />
                    <label for="code">Mã sản phẩm</label>
                </div>
            </div> --}}
            {{-- <div class="col-md-6">
                <div class="form-check form-switch mt-4">
                    <label for="inventory">Mở bán</label>
                    <input id="inventory" class="form-check-input float-start" type="checkbox"
                        value="1" name="inventory" role="switch"
                        {{ @$data->inventory ? 'checked' : null }}>
                </div>
            </div> --}}
            {{-- <x-gallery name="gallery" :data-component="@$data->images" field="image" type="Images" /> --}}
        </div>
    
         
        <!-- /Account -->
    </div>

    {{-- <div class=" mt-4">
        <div class="">
            <div class="col-md-12">
                <x-editor_v2 name="summary" id="summary" title="Đặc điểm nổi bật"
                    content="{{ @$data->summary }}" />
            </div>
        </div>
    </div> --}}

  

    {{-- <div class="mt-4">
        <div class="">
            <div class="col-md-12">
                <x-editor_v2 name="guide_management" id="guide_management" title="Hướng dẫn bảo quản"
                    content="{{ @$data->guide_management }}" />
            </div>
        </div>
    </div> --}}
    {{-- <div class="mt-4">
        <div class="">
            <div class="col-md-12">
                <x-editor_v2 name="return_policy" id="return_policy" title="Chính sách đổi trả"
                    content="{{ @$data->return_policy }}" />
            </div>
        </div>
    </div> --}}
</form>
