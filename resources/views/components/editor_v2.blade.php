<div class="mt-3">
    @if (@$title)
        <label for="lastName">{{ $title }}</label>
    @endif
    <div class="editor-container editor-container_classic-editor mt-3" id="editor-container">
        <div class="data-id" data-id="{{ $id }}" class="editor-container__editor">
            <div id="{{ $id }}"></div>
        </div>
    </div>
    <input type="hidden" id="{{ $id }}-value" name="{{ $name }}" form="formAccountSettings"
        value="{{ htmlspecialchars_decode($content) }}"></input>
</div>

@section('script_page')

    <script type="importmap">
    {
        "imports": {
            "ckeditor5":"{{asset('assets/admin/js/ckeditor5/ckeditor5.js')}}",
            "ckeditor5/":"{{asset('assets/admin/js/ckeditor5/')}}/"
        }
    }
    </script>
    <script type="module" src="{{ asset('assets/admin/js/ckeditor5-config.js') }}"></script>

@endsection
