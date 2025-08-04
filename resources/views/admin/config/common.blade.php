@foreach (@$list as $item)
    @if ($item->published)
        @if ($item->type == 'text')
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <input name="{{ $item->alias }}" type="text" class="form-control" value="{{ @$item->value }}"
                            id="{{ @$item->alias }}" placeholder="">
                        <label for="{{ @$item->alias }}">{{ @$item->title }}</label>
                    </div>
                </div>
            </div>
        @endif
        @if ($item->type == 'file')
            @php
                $type = '';
                if (strpos(@$item->value, '.') !== false) {
                    $parts = explode('.', @$item->value);
                    $type = end($parts);
                }
                $dataComponent = (object) [
                    'url' => @$item->value,
                    'type' => $type,
                ];
            @endphp
            <div class="col-md-4 mb-4">
                <x-choose-file :title="$item->title" :type="'Images'" :id="$item->alias" :dataComponent="$dataComponent" />
            </div>
        @endif
    @endif
@endforeach
