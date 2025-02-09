@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm bg-white p-3 text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
