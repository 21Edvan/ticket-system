@props([
    'class' => '',
])

<div
    {{ $attributes->merge([
        'class' =>
            'flex items-center gap-3 '.$class
    ]) }}
>

    @if ($company->logoUrl())

        <img
            src="{{ $company->logoUrl() }}"
            alt="{{ $company->company_name }}"
            class="
                max-h-12
                max-w-[200px]
                object-contain
            "
        >

    @else

        <div
            class="
                flex h-11 w-11
                items-center justify-center
                rounded-xl
                text-lg
                font-bold
                text-white
            "

            style="
                background-color:
                {{ $company->primary_color ?? '#4F46E5' }};
            "
        >
            {{ strtoupper(
                substr(
                    $company->company_name ?? 'E',
                    0,
                    1
                )
            ) }}
        </div>

    @endif

</div>