@if (!empty(@$groupFilter))
    @foreach (@$groupFilter as $groupName => $group)
        <div class="border rounded-3 p-3 mb-4">
            <div class="mb-4 fs-6">
                <b>{{ @$groupName }}</b>
            </div>
            <div class="row row-gap-4">
                @foreach ($group as $item)
                    @php
                        $formName = "filter[{$item->filter_category_id}]";
                        $formId = 'filter-' . $item->filter_category_id;
                    @endphp
                    <div class="col-md-6">
                        @switch (@$item->category->type)
                            @case('single')
                                <label class="d-block mb-3" for="{{ $formId }}">{{ $item->name }}</label>
                                <select form="formAccountSettings" name="{{ $formName }}" id="{{ $formId }}" class="form-select select2 form-select-sm">
                                    <option value="">---Chọn {{ $item->name }}---</option>
                                    @foreach ($filterSelectAvailable as $select)
                                        @if ($select->filter_category_id == $item->filter_category_id)
                                            <option value="{{ $select->id }}" {{ $select->id == $item->value ? 'selected' : '' }}>
                                                {{ $select->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @break;
                            @case('multiple')
                                <label class="d-block mb-3" for="{{ $formId }}">{{ $item->name }}</label>
                                <select form="formAccountSettings" name="{{ $formName }}[]" id="{{ $formId }}" multiple class="form-select select2 form-select-sm">
                                    <option value="">---Chọn {{ $item->name }}---</option>
                                    @foreach ($filterSelectAvailable as $select)
                                        @if ($select->filter_category_id == $item->filter_category_id)
                                            <option value="{{ $select->id }}" {{ in_array($select->id, array_filter(explode(',', $item->value))) ? 'selected' : '' }}>
                                                {{ $select->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @break;
                            @case('textarea')
                                <div style="margin-top: -1rem;">
                                    <x-editor_v2 name="{{ $formName }}" id="{{ $formId }}" title="{{ $item->name }}" content="{{ @$item->value }}" />
                                </div>
                                @break;
                            @default
                                <label class="d-block mb-3" for="{{ $formId }}">{{ $item->name }}</label>
                                <div class="form-floating form-floating-outline">
                                    <input form="formAccountSettings" class="form-control" type="text" id="{{ $formId }}" name="{{ $formName }}" 
                                        value="{{ $item->value }}" placeholder="{{ $item->name }}" />
                                    <label for="{{ $formId }}">{{ $item->name }}</label>
                                </div>
                            @break;
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="fw-medium">Vui lòng lưu sản phẩm trước khi thêm thông số kỹ thuật</div>
@endif
<style>
    .select2.select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        line-height: 35px;
    }
</style>