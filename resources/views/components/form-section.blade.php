@props([
    'submit'
])

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

    <form wire:submit="{{ $submit }}">

        {{-- ========================================================
            CABECERA
        ======================================================== --}}
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


        {{-- ========================================================
            FORMULARIO
        ======================================================== --}}
        <div class="p-5">

            <div
                class="
                    grid
                    grid-cols-6
                    gap-5
                "
            >
                {{ $form }}
            </div>

        </div>


        {{-- ========================================================
            ACCIONES
        ======================================================== --}}
        @if (isset($actions))

            <div
                class="
                    flex
                    items-center
                    justify-end
                    gap-3
                    border-t
                    border-gray-100
                    bg-gray-50
                    px-5 py-3
                "
            >
                {{ $actions }}
            </div>

        @endif

    </form>

</div>