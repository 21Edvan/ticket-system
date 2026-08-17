<button
    {{
        $attributes->merge([
            'type' => 'button',

            'class' =>
                '
                    inline-flex
                    items-center
                    justify-center
                    rounded-md
                    border
                    border-gray-300
                    bg-white
                    px-4 py-2
                    text-xs
                    font-semibold
                    uppercase
                    tracking-widest
                    text-gray-700
                    shadow-sm
                    transition

                    hover:bg-gray-50
                    hover:text-gray-900

                    focus:outline-none

                    disabled:cursor-not-allowed
                    disabled:opacity-50
                '
        ])
    }}
>
    {{ $slot }}
</button>