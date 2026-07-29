@if (isset($label) && $label != false)
    @php
        $for_id = preg_replace('/[^A-Za-z0-9\-]/', '', Str::lower($label));
    @endphp
    <label for="{{ $for_id ?? "" }}">{!! $label ?? "" !!}</label>
@endif
<input type="file" class="{{ $class ?? "" }}" name="{{ $name ?? "" }}" accept="{{ $accept ?? "image/*" }}" 
    @if (isset($old_files) && $old_files != "")
        data-preview-name= "{{ $old_files }}"
    @endif
    @if (isset($old_files_path) && $old_files_path != "")
        data-preview-path="{{ $old_files_path }}"
    @endif
{{ $attribute ?? "" }} id="{{ $for_id ?? "" }}">
@if (isset($errors) && $errors->has($name ?? ''))
    <span class="invalid-feedback d-block" role="alert">
        <strong>{{ $errors->first($name ?? '') }}</strong>
    </span>
@endif