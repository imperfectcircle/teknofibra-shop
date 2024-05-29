@props(['disabled' => false, 'type' => 'text'])

<div class="">
    @if ($type === 'select')
        <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full']) !!}>
            {{ $slot }}
        </select>
    @else
        <input {{ $disabled ? 'disabled' : '' }} type="{{ $type }}" {!! $attributes->merge(['class' => 'border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full']) !!}>
    @endif
</div>
