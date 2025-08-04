<p>{{$title}}</p>
<div class="editor-ace" id="{{ $id }}_ace_editor">{!! $content !!}</div>
<input type="hidden" id="{{ $id }}_ace_value" name="{{ $name }}"
value="{{ htmlspecialchars_decode($content) }}"></input>

@push('push_script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ace_editor['{{$id}}'+ '_ace_editor'] = ace.edit("{{ $id }}" + '_ace_editor');
        ace_editor['{{$id}}'+ '_ace_editor'].setTheme("ace/theme/monokai");
        ace_editor['{{$id}}'+ '_ace_editor'].session.setMode("ace/mode/javascript");
        ace_editor['{{$id}}'+ '_ace_editor'].resize();
    });
</script>
@endpush
