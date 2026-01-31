@props(['label', 'error' => null, 'hint' => null, 'required' => false])

<div class="{{ $attributes->get('class') }}">
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="mt-1">
        {{ $slot }}
    </div>
    
    @if($hint)
        <p class="mt-1 text-sm text-gray-500">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
