@props([
    'disabled' => false
])

<input
    @disabled($disabled)

    {{
        $attributes->merge([
            'class' =>
                '
                    brand-focus
                    rounded-md
                    border-gray-300
                    text-sm
                    shadow-sm

                    disabled:bg-gray-100
                    disabled:text-gray-500
                '
        ])
    }}
>