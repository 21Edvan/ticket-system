<button
    {{
        $attributes->merge([
            'type' => 'submit',

            'class' =>
                '
                    inline-flex
                    items-center
                    justify-center
                    rounded-md
                    border
                    border-transparent
                    px-4 py-2
                    text-xs
                    font-semibold
                    uppercase
                    tracking-widest
                    text-white
                    shadow-sm
                    transition

                    disabled:cursor-not-allowed
                    disabled:opacity-50
                '
        ])
    }}

    style="
        background-color:
        {{ $company->primary_color ?? '#4F46E5' }};
    "
>
    {{ $slot }}
</button>