@foreach (@$list as $item)
    @if ($item->published)
        @if ($item->type == 'editor')
            <div class="col-md-12 mb-4">
                <x-editor_v2 :name="$item->alias" :id="$item->alias" :title="$item->title" content="{{ @$item->value }}" />
            </div>
        @endif
    @endif
@endforeach
