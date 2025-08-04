<form action="{{ route('filter') }}" method="POST">
    @csrf
    <div class="d-flex">
        <div class="form-floating form-floating-outline">
            <input class="form-control" type="text" id="{{ $prefix ?? '' }}_keyword_filter"
                name="{{ $prefix ?? '' }}_keyword_filter" value="{{ $filterValue[$prefix . '_keyword_filter'] ?? '' }}"
                placeholder="">
            <label for="ordering">Tìm kiếm...</label>
        </div>
        @if (isset($categories) && count($categories) > 0)
            <div class="col-md-3 ms-3">
                <select name="{{ $prefix ?? '' }}_category_id_filter" aria-controls="DataTables_Table_0"
                    class="form-select select2 form-select-sm">
                    <option value="0">Lọc theo danh mục</option>
                    @foreach (@$categories as $category)
                        <option
                            {{ isset($filterValue[$prefix . '_category_id_filter']) && $category->id == $filterValue[$prefix . '_category_id_filter'] ? 'selected' : null }}
                            value="{{ $category->id }}">{!! $category->treename ? $category->treename : $category->name !!}</option>
                    @endforeach
                </select>
            </div>
        @endif
        {{ $slot }}
        <button type="submit" class="ms-3 btn btn-primary waves-effect waves-light">Lọc</button>
    </div>
</form>
