<div>
    <label for="lastName">{{ $title }}</label>
    <textarea name="{{ $name }}" id="{{ $id }}" class="editor" data-editor="ClassicEditor"
        data-collaboration="false" data-revision-history="false">
    {{ htmlspecialchars_decode($content) }}
    </textarea>
</div>

@push('push_script')
    <script>
        ClassicEditor
            .create(document.querySelector(`#{{ $id }}`), {
                removePlugins: ['Markdown', 'MediaEmbedToolbar'],
                ckfinder: {
                    uploadUrl: '/ckfinder/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                },
                language: 'en' // hoặc 'vi' nếu bạn có file lang/vi.js

            })
            .then(editor => {
                window.editor = editor;
            })
            .catch();
    </script>
@endpush
