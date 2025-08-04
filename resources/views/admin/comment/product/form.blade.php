@extends('admin.contentNavLayout')
@php
    $title = 'Chi tiết đánh giá';
@endphp

@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection


@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-0">
            Chi tiết đánh giá
        </h4>
        <div>
            {{-- <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
            <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
            <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button> --}}
            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href = '{{ route($view . '.index') }}'">Đóng</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body pt-2 mt-1">
                    <div class="mb-4 d-flex align-items-center gap-3"> 
                        <a class="d-flex gap-3" target="_blank" href="{{ @$product->href }}">
                            <img src="{{ asset(@$product->image) }}" alt="" class="img-fluid" style="width: 100px; height: 100px;">
                            <div class="">{{ @$product->name }}</div> 
                        </a>
                        
                        <div class="fs-6 fw-semibold d-flex align-items-end gap-1">
                            {{ $avg }} <i class="menu-icon tf-icons mdi mdi-star" style="color: #F8B52B;"></i>
                        </div>
                        <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2" cy="2" r="2" fill="#BFBFBF"/>
                        </svg>
                        <div class="fs-6 fw-semibold">
                            {{ $total }} đánh giá
                        </div>
                        <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2" cy="2" r="2" fill="#BFBFBF"/>
                        </svg>
                        <div class="fs-6 fw-semibold">
                            {{ $helpfull }} hữu ích
                        </div>
                        <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2" cy="2" r="2" fill="#BFBFBF"/>
                        </svg>
                        <div class="fs-6 fw-semibold">
                            {{ $unread }} chưa trả lời
                        </div>
                    </div>

                    <div>
                        @foreach ($data as $index => $item) 
                            <div class="mt-3 p-3 rounded border {{ $item->read ? 'border-success' : 'border-danger' }}">
                                <div class="d-flex gap-3 align-items-center mb-1">
                                    <div class="d-flex align-items-end">
                                        {{ $item->rate }} <i class="menu-icon tf-icons mdi mdi-star" style="color: #F8B52B;"></i>
                                    </div>
                                    <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="2" cy="2" r="2" fill="#BFBFBF"/>
                                    </svg>
                                    <div>
                                        {{ $item->helpfull }} hữu ích
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <b class="text-black">{{ $item->name }}</b>
                                    <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="2" cy="2" r="2" fill="#BFBFBF"/>
                                    </svg>
                                    <b class="text-black">{{ $item->telephone }}</b>
                                    <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="2" cy="2" r="2" fill="#BFBFBF"/>
                                    </svg>
                                    <span>{{ $item->created_at->format('d-m-Y H:i:s') }}</span>
                                </div>
                                <div class="mb-2">
                                    {!! $item->content !!}
                                </div>
                                <div class="mb-3 d-flex gap-2">
                                    @foreach ($item->images as $image)
                                        <img src="{{ asset($image->image) }}" alt="" class="img-fluid rounded-2" style="width: 80px; height: 80px; object-fit: cover;">
                                    @endforeach
                                </div>                            

                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input form-check-published" type="checkbox" role="switch" id="published-{{ $item->id}}" {{ $item->published ? 'checked' : null }}>
                                        <label class="form-check-label" for="published-{{ $item->id}}">Hiển thị</label>
                                    </div>
                                    <a data-bs-toggle="collapse" 
                                        class="d-inline-flex align-items-center"
                                        href="#collapseComment-{{ $item->id }}" 
                                        role="button" 
                                        aria-expanded="false" 
                                        aria-controls="collapseComment-{{ $item->id }}">
                                        Trả lời
                                        <i class="menu-icon tf-icons mdi mdi-chevron-down"></i>
                                    </a>
                                </div>

                                <div class="collapse" id="collapseComment-{{ $item->id }}">
                                    <div class="pt-3 collapse-body">
                                        @csrf
                                        <div>
                                            <a href=""
                                                data-parent-id="{{ $item->id }}" 
                                                data-id="{{ @$item->comments[0]->id ?: 0 }}" 
                                                class="btn btn-primary save-comment">
                                                Gửi
                                            </a>
                                        </div>
                                        <div class="form-floating form-floating-outline mt-3">
                                            <input class="form-control" type="text" name="name" value="{{ @$item->comments[0]->name }}" placeholder="" />
                                            <label for="ordering">Tên</label>
                                        </div> 
                                        <x-editor_v2 name="content" id="content-{{ $item->id }}" title="" content="{{ @$item->comments[0]->content }}" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection

@push('push_script')
    <script>
        $('.form-check-published').on('change', function(){
            let id = $(this).attr('id').split('-')[1]
            let published = 0
            if ($(this).is(':checked')) {
                published = 1
            }

            let data = {
                id,
                published,
                _token: $('meta[name=csrf-token]').attr('content')
            }

            $.ajax({
                url: '{{ route('admin.comment.product.save') }}',
                data,
                type: 'POST',
                success: function(response) {
                    Swal.fire({
                        icon: response.error ? "error" : "success",
                        title: response.error ? "Thất bại" : "Thành công!",
                        text: response.message, 
                    })
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching component:', error)
                }
            })
        })

        $('.save-comment').on('click', function(e) {
            e.preventDefault() 

            let id = $(this).data('id')
            let parent_id = $(this).data('parent-id')
            let content = window.editors[`editor_content-${parent_id}`].getData()
            let name = $(this).closest('.collapse-body').find('input[name="name"]').val()
            let data = {
                id,
                name,
                content,
                parent_id,
                user_id: {{ Auth::id() }},
                read: 1,
                _token: $('meta[name=csrf-token]').attr('content')
            }

            $.ajax({
                url: '{{ route('admin.comment.product.save') }}',
                data,
                type: 'POST',
                success: function(response) {
                    Swal.fire({
                        icon: response.error ? "error" : "success",
                        title: response.error ? "Thất bại" : "Thành công!",
                        text: response.message, 
                    })
                    location.reload()
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching component:', error)
                }
            })
        })
    </script>
@endpush