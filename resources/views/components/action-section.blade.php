<div
    {{ $attributes->merge([
        'class' =>
            '
                overflow-hidden
                rounded-xl
                border
                border-gray-200
                bg-white
                shadow-sm
            '
    ]) }}
>

    {{-- HEADER --}}
    <div
        class="
            border-b
            border-gray-200
            px-5 py-4
        "
    >

        <h2
            class="
                text-base
                font-semibold
                text-gray-900
            "
        >
            {{ $title }}
        </h2>

        <div
            class="
                mt-1
                max-w-3xl
                text-sm
                leading-6
                text-gray-500
            "
        >
            {{ $description }}
        </div>

    </div>


    {{-- CONTENIDO --}}
    <div class="p-5">

        {{ $content }}

    </div>

</div>