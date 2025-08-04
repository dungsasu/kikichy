@foreach (@$list as $item)
    @if ($item->published)
        @if ($item->type == 'code')
            <div class="col-md-12 mb-4">
                <x-code_block :name="$item->alias" :id="$item->alias" :title="$item->title" content="{{ @$item->value }}" />
            </div>
        @endif
    @endif
@endforeach
