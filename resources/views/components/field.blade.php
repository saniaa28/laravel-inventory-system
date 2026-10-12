{{-- Komponen field form: label, input/select/textarea, dan pesan error dalam satu tempat --}}
@props(['label', 'name', 'type' => 'text', 'value' => null])
<label class="{{ $attributes->get('class') }}">
    {{ $label }}
    @if($type === 'select')
        <select name="{{ $name }}" {{ $attributes->except('class') }}>{{ $slot }}</select>
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" rows="3" {{ $attributes->except('class') }}>{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" {{ $attributes->except('class') }}>
    @endif
    @error($name)
        <small class="field-error">{{ $message }}</small>
    @enderror
</label>
