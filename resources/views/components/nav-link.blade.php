@props([
    'active' => false,
])

@php
    $baseClasses = '
        inline-flex
        items-center
        border-b-2
        px-1
        pt-1
        text-sm
        font-medium
        leading-5
        transition
        duration-150
        ease-in-out
        focus:outline-none
    ';
@endphp

<a
    {{ $attributes->merge([
        'class' =>
            $baseClasses
            .' '
            .(
                $active
                    ? 'border-brand text-gray-900'
                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
            )
    ]) }}
>
    {{ $slot }}
</a>