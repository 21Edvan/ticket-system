<a
    href="/"
    class="
        inline-flex
        flex-col
        items-center
        gap-3
    "
>

    @if ($company->logoUrl())

        <img
            src="{{ $company->logoUrl() }}"
            alt="{{ $company->company_name }}"
            class="
                max-h-20
                max-w-[240px]
                object-contain
            "
        >

    @else

        <div
            class="
                flex
                h-16 w-16
                items-center
                justify-center
                rounded-2xl
                text-2xl
                font-bold
                text-white
                shadow-lg
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

</a>