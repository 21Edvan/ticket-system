@props([
    'active' => false,
])

<a
    {{ $attributes->merge([
        'class' =>
            '
                block
                w-full
                border-l-4
                py-2
                pe-4
                ps-3
                text-start
                text-base
                font-medium
                transition
                duration-150
                ease-in-out
                focus:outline-none
            '
            .' '
            .(
                $active
                    ? 'border-brand bg-gray-50 text-brand'
                    : 'border-transparent text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800'
            )
    ]) }}
>
    {{ $slot }}
</a>