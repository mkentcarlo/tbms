@props(['options' => [], 'placeholder' => 'Select an option...', 'error' => null])

<select {{ $attributes->merge(['class' => 'form-input ' . ($error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '')]) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($options as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
    {{ $slot }}
</select>

@if($error)
    <p class="form-error">{{ $error }}</p>
@endif
