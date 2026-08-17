<div
    x-data="{
        open: false,

        scrollBottom() {
            this.$nextTick(() => {
                if (this.$refs.messages) {
                    this.$refs.messages.scrollTop =
                        this.$refs.messages.scrollHeight;
                }
            });
        }
    }"

    wire:poll.5s="refreshComments"

    x-on:comments-updated.window="
        if (open) {
            $wire.markAsRead();
            scrollBottom();
        }
    "
>

    {{-- ============================================================
        BOTÓN FLOTANTE
    ============================================================ --}}
    <button
        type="button"
        x-show="! open"
        x-cloak

        x-on:click="
            open = true;
            $wire.markAsRead();
            scrollBottom();
        "

        class="
            fixed bottom-6 right-6 z-50
            flex items-center gap-2
            rounded-full
            bg-indigo-600
            px-5 py-4
            font-semibold text-white
            shadow-xl
            transition
            hover:bg-indigo-500
        "
    >

        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01
                   M21 12c0 4.418-4.03 8-9 8
                   a9.863 9.863 0 01-4.255-.949
                   L3 20l1.395-3.72
                   C3.512 15.042 3 13.574 3 12
                   c0-4.418 4.03-8 9-8s9 3.582 9 8z"
            />
        </svg>


        <span class="hidden sm:inline">
            Soporte
        </span>


        @if ($unreadCount > 0)

            <span
                class="
                    flex min-w-6
                    items-center justify-center
                    rounded-full
                    bg-red-500
                    px-2 py-1
                    text-xs
                    font-bold
                    text-white
                "
            >
                {{ $unreadCount > 99
                    ? '99+'
                    : $unreadCount
                }}
            </span>

        @endif

    </button>


    {{-- ============================================================
        PANEL DE CHAT
    ============================================================ --}}
    <div
        x-show="open"
        x-cloak

        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"

        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"

        class="
            fixed
            bottom-3 left-3 right-3
            z-50

            flex
            min-h-0
            flex-col

            overflow-hidden

            rounded-2xl
            bg-white
            shadow-2xl
            ring-1 ring-gray-200

            sm:bottom-6
            sm:left-auto
            sm:right-6
            sm:w-[420px]
        "

        style="
            height: min(680px, calc(100dvh - 48px));
            max-height: calc(100dvh - 48px);
        "
    >

        {{-- ========================================================
            HEADER
        ======================================================== --}}
        <div
            class="
                flex
                shrink-0
                items-center
                justify-between
                border-b
                bg-white
                px-5 py-4
            "
        >

            <div class="min-w-0">

                <p class="font-semibold text-gray-900">
                    Soporte
                </p>

                <p class="truncate text-xs text-gray-500">
                    {{ $ticket->ticket_number }}
                    ·
                    {{ $ticket->status->label() }}
                </p>

            </div>


            <button
                type="button"
                x-on:click="open = false"

                class="
                    ml-3
                    shrink-0
                    rounded-full
                    p-2
                    text-gray-500
                    transition
                    hover:bg-gray-100
                    hover:text-gray-900
                "
            >

                <span class="sr-only">
                    Cerrar chat
                </span>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

            </button>

        </div>


        {{-- ========================================================
            TÉCNICO
        ======================================================== --}}
        <div
            class="
                shrink-0
                border-b
                bg-gray-50
                px-5 py-3
            "
        >

            @if ($ticket->technician)

                <p class="truncate text-sm font-medium text-gray-800">
                    {{ $ticket->technician->name }}
                </p>

                <p class="text-xs text-gray-500">
                    Técnico asignado
                </p>

            @else

                <p class="text-sm text-gray-500">
                    Esperando asignación de técnico
                </p>

            @endif

        </div>


        {{-- ========================================================
            MENSAJES
        ======================================================== --}}
        <div
            x-ref="messages"

            class="
                min-h-0
                flex-1
                space-y-4

                overflow-x-hidden
                overflow-y-auto
                overscroll-contain

                bg-gray-50
                p-4
            "
        >

            @forelse ($comments as $comment)

                @php
                    $isMine =
                        $comment->user_id === auth()->id();

                    $hasBody =
                        trim($comment->body ?? '') !== '';
                @endphp


                <div
                    wire:key="comment-{{ $comment->id }}"

                    class="
                        flex
                        min-w-0

                        {{ $isMine
                            ? 'justify-end'
                            : 'justify-start'
                        }}
                    "
                >

                    <div class="min-w-0 max-w-[88%]">

                        {{-- ============================================
                            AUTOR
                        ============================================ --}}
                        <div
                            class="
                                mb-1
                                flex
                                items-center
                                gap-2
                                text-xs

                                {{ $isMine
                                    ? 'justify-end'
                                    : 'justify-start'
                                }}
                            "
                        >

                            <span class="truncate font-medium text-gray-700">
                                {{ $comment->user->name }}
                            </span>


                            @if ($comment->user->isAdmin())

                                <span class="shrink-0 text-purple-600">
                                    Administrador
                                </span>

                            @elseif ($comment->user->isTechnician())

                                <span class="shrink-0 text-blue-600">
                                    Técnico
                                </span>

                            @else

                                <span class="shrink-0 text-gray-500">
                                    Usuario
                                </span>

                            @endif

                        </div>


                        {{-- ============================================
                            TEXTO
                        ============================================ --}}
                        @if ($hasBody)

                            <div
                                @class([
                                    'break-words rounded-2xl px-4 py-3 text-sm shadow-sm',

                                    'rounded-br-md bg-indigo-600 text-white'
                                        => $isMine,

                                    'rounded-bl-md border bg-white text-gray-800'
                                        => ! $isMine,
                                ])
                            >
                                {!! nl2br(
                                    e($comment->body)
                                ) !!}
                            </div>

                        @endif


                        {{-- ============================================
                            ADJUNTOS
                        ============================================ --}}
                        @if ($comment->attachments->isNotEmpty())

                            <div
                                class="
                                    min-w-0
                                    space-y-2

                                    {{ $hasBody
                                        ? 'mt-2'
                                        : ''
                                    }}
                                "
                            >

                                @foreach ($comment->attachments as $attachment)

                                    {{-- =================================
                                        IMAGEN
                                    ================================= --}}
                                    @if ($attachment->isImage())

                                        <div
                                            class="
                                                max-w-full
                                                overflow-hidden
                                                rounded-xl
                                                border
                                                border-gray-200
                                                bg-white
                                                shadow-sm
                                            "
                                        >

                                            {{-- PREVIEW LIMITADO --}}
                                            <a
                                                href="{{ route(
                                                    'attachments.show',
                                                    $attachment
                                                ) }}"

                                                target="_blank"

                                                class="
                                                    flex
                                                    w-full
                                                    items-center
                                                    justify-center
                                                    overflow-hidden
                                                    bg-gray-100
                                                "

                                                style="
                                                    height: 180px;
                                                    max-height: 180px;
                                                "
                                            >

                                                <img
                                                    src="{{ route(
                                                        'attachments.show',
                                                        $attachment
                                                    ) }}"

                                                    alt="{{ $attachment->original_name }}"

                                                    loading="lazy"

                                                    class="
                                                        block
                                                        object-contain
                                                        transition
                                                        hover:opacity-90
                                                    "

                                                    style="
                                                        width: auto;
                                                        height: auto;
                                                        max-width: 100%;
                                                        max-height: 180px;
                                                    "
                                                >

                                            </a>


                                            {{-- DATOS --}}
                                            <div
                                                class="
                                                    flex
                                                    min-w-0
                                                    items-center
                                                    justify-between
                                                    gap-2
                                                    px-3 py-2
                                                "
                                            >

                                                <div class="min-w-0 flex-1">

                                                    <p
                                                        class="
                                                            truncate
                                                            text-xs
                                                            font-medium
                                                            text-gray-700
                                                        "

                                                        title="{{ $attachment->original_name }}"
                                                    >
                                                        {{ $attachment->original_name }}
                                                    </p>

                                                    <p class="text-[10px] text-gray-400">
                                                        {{ $attachment->formattedSize() }}
                                                    </p>

                                                </div>


                                                <a
                                                    href="{{ route(
                                                        'attachments.download',
                                                        $attachment
                                                    ) }}"

                                                    class="
                                                        shrink-0
                                                        rounded-md
                                                        p-1.5
                                                        text-indigo-600
                                                        transition
                                                        hover:bg-indigo-50
                                                    "

                                                    title="Descargar"
                                                >

                                                    <svg
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14"
                                                        />
                                                    </svg>

                                                </a>

                                            </div>

                                        </div>


                                    {{-- =================================
                                        DOCUMENTO
                                    ================================= --}}
                                    @else

                                        <div
                                            class="
                                                flex
                                                min-w-0
                                                max-w-full
                                                items-center
                                                gap-3
                                                overflow-hidden
                                                rounded-xl
                                                border
                                                border-gray-200
                                                bg-white
                                                p-3
                                                shadow-sm
                                            "
                                        >

                                            <div
                                                class="
                                                    flex
                                                    h-10 w-10
                                                    shrink-0
                                                    items-center
                                                    justify-center
                                                    rounded-lg
                                                    bg-indigo-50
                                                    text-indigo-600
                                                "
                                            >

                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zm0 0v6h6M8 13h8M8 17h5"
                                                    />
                                                </svg>

                                            </div>


                                            <div class="min-w-0 flex-1">

                                                <a
                                                    href="{{ route(
                                                        'attachments.show',
                                                        $attachment
                                                    ) }}"

                                                    target="_blank"

                                                    class="
                                                        block
                                                        truncate
                                                        text-xs
                                                        font-semibold
                                                        text-gray-700
                                                        hover:text-indigo-600
                                                        hover:underline
                                                    "

                                                    title="{{ $attachment->original_name }}"
                                                >
                                                    {{ $attachment->original_name }}
                                                </a>

                                                <p class="mt-0.5 text-[10px] text-gray-400">
                                                    {{ $attachment->formattedSize() }}
                                                </p>

                                            </div>


                                            <a
                                                href="{{ route(
                                                    'attachments.download',
                                                    $attachment
                                                ) }}"

                                                class="
                                                    shrink-0
                                                    rounded-md
                                                    p-2
                                                    text-indigo-600
                                                    transition
                                                    hover:bg-indigo-50
                                                "

                                                title="Descargar"
                                            >

                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14"
                                                    />
                                                </svg>

                                            </a>

                                        </div>

                                    @endif

                                @endforeach

                            </div>

                        @endif


                        {{-- ============================================
                            FECHA
                        ============================================ --}}
                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400

                                {{ $isMine
                                    ? 'text-right'
                                    : 'text-left'
                                }}
                            "
                        >
                            {{ $comment->created_at->format(
                                'd/m/Y H:i'
                            ) }}
                        </p>

                    </div>

                </div>


            @empty

                <div
                    class="
                        flex
                        h-full
                        min-h-0
                        items-center
                        justify-center
                        text-center
                        text-sm
                        text-gray-500
                    "
                >

                    <div>

                        <p class="font-medium">
                            No hay mensajes todavía
                        </p>

                        <p class="mt-1 text-xs">
                            Utiliza el chat para comunicarte sobre este ticket.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>


        {{-- ========================================================
            FORMULARIO
        ======================================================== --}}
        <div
            class="
                shrink-0
                border-t
                bg-white
                p-4
            "
        >

            @can('comment', $ticket)

                <div
                    x-data="{
                        uploading: false,
                        progress: 0
                    }"

                    x-on:livewire-upload-start="
                        uploading = true
                    "

                    x-on:livewire-upload-finish="
                        uploading = false;
                        progress = 0;
                    "

                    x-on:livewire-upload-error="
                        uploading = false;
                        progress = 0;
                    "

                    x-on:livewire-upload-cancel="
                        uploading = false;
                        progress = 0;
                    "

                    x-on:livewire-upload-progress="
                        progress = $event.detail.progress
                    "
                >

                    {{-- ================================================
                        ARCHIVOS SELECCIONADOS
                    ================================================ --}}
                    @if (count($attachments) > 0)

                        <div
                            class="
                                mb-3
                                max-h-36
                                space-y-2
                                overflow-y-auto
                                rounded-lg
                                bg-gray-50
                                p-2
                            "
                        >

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    px-1
                                "
                            >

                                <span
                                    class="
                                        text-[11px]
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-gray-500
                                    "
                                >
                                    Adjuntos
                                </span>

                                <span class="text-[11px] text-gray-400">
                                    {{ count($attachments) }}/5
                                </span>

                            </div>


                            @foreach ($attachments as $index => $file)

                                <div
                                    wire:key="chat-attachment-{{ $index }}-{{ md5($file->getClientOriginalName()) }}"

                                    class="
                                        flex
                                        min-w-0
                                        items-center
                                        gap-2
                                        rounded-lg
                                        border
                                        border-gray-200
                                        bg-white
                                        p-2
                                    "
                                >

                                    {{-- PREVIEW --}}
                                    @if (
                                        str_starts_with(
                                            $file->getMimeType() ?? '',
                                            'image/'
                                        )
                                    )

                                        <img
                                            src="{{ $file->temporaryUrl() }}"

                                            alt="{{ $file->getClientOriginalName() }}"

                                            class="
                                                h-10 w-10
                                                shrink-0
                                                rounded-md
                                                object-cover
                                            "
                                        >

                                    @else

                                        <div
                                            class="
                                                flex
                                                h-10 w-10
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-md
                                                bg-indigo-50
                                                text-indigo-600
                                            "
                                        >

                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zm0 0v6h6"
                                                />
                                            </svg>

                                        </div>

                                    @endif


                                    <div class="min-w-0 flex-1">

                                        <p
                                            class="
                                                truncate
                                                text-xs
                                                font-medium
                                                text-gray-700
                                            "

                                            title="{{ $file->getClientOriginalName() }}"
                                        >
                                            {{ $file->getClientOriginalName() }}
                                        </p>


                                        <p class="text-[10px] text-gray-400">

                                            @if (
                                                $file->getSize()
                                                >= 1024 * 1024
                                            )

                                                {{ number_format(
                                                    $file->getSize()
                                                    / 1024
                                                    / 1024,
                                                    2
                                                ) }} MB

                                            @else

                                                {{ number_format(
                                                    $file->getSize()
                                                    / 1024,
                                                    1
                                                ) }} KB

                                            @endif

                                        </p>

                                    </div>


                                    <button
                                        type="button"

                                        wire:click="
                                            removeAttachment(
                                                {{ $index }}
                                            )
                                        "

                                        class="
                                            shrink-0
                                            rounded-md
                                            p-1.5
                                            text-gray-400
                                            transition
                                            hover:bg-red-50
                                            hover:text-red-600
                                        "

                                        title="Quitar archivo"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>

                                    </button>

                                </div>

                            @endforeach

                        </div>

                    @endif


                    {{-- ================================================
                        PROGRESO
                    ================================================ --}}
                    <div
                        x-show="uploading"
                        x-cloak
                        class="mb-3"
                    >

                        <div
                            class="
                                mb-1
                                flex
                                items-center
                                justify-between
                            "
                        >

                            <span class="text-[11px] text-gray-500">
                                Cargando...
                            </span>

                            <span
                                class="
                                    text-[11px]
                                    font-semibold
                                    text-indigo-600
                                "

                                x-text="progress + '%'"
                            ></span>

                        </div>


                        <div
                            class="
                                h-1.5
                                overflow-hidden
                                rounded-full
                                bg-gray-200
                            "
                        >

                            <div
                                class="
                                    h-full
                                    rounded-full
                                    bg-indigo-600
                                    transition-all
                                "

                                x-bind:style="
                                    'width: ' + progress + '%'
                                "
                            ></div>

                        </div>

                    </div>


                    {{-- ================================================
                        ERRORES
                    ================================================ --}}
                    <x-input-error
                        for="attachments"
                        class="mb-2"
                    />

                    @if ($errors->has('attachments.*'))

                        <p class="mb-2 text-xs text-red-600">
                            {{ $errors->first('attachments.*') }}
                        </p>

                    @endif


                    {{-- ================================================
                        FORMULARIO DE ENVÍO
                    ================================================ --}}
                    <form
                        wire:submit="addComment"

                        class="
                            flex
                            min-w-0
                            items-end
                            gap-2
                        "
                    >

                        {{-- ADJUNTAR --}}
                        <label
                            for="chat-attachments"

                            @class([
                                '
                                    flex
                                    h-11 w-11
                                    shrink-0
                                    cursor-pointer
                                    items-center
                                    justify-center
                                    rounded-full
                                    border
                                    border-gray-300
                                    bg-white
                                    text-gray-500
                                    transition
                                    hover:border-indigo-400
                                    hover:bg-indigo-50
                                    hover:text-indigo-600
                                ',

                                'pointer-events-none opacity-40'
                                    => count($attachments) >= 5,
                            ])

                            title="Adjuntar archivo"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828a4 4 0 10-5.657-5.657L5.757 10.757a6 6 0 108.486 8.486L20.5 13"
                                />
                            </svg>

                        </label>


                        <input
                            id="chat-attachments"
                            type="file"
                            wire:model="attachments"
                            multiple

                            accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt"

                            @disabled(
                                count($attachments) >= 5
                            )

                            class="hidden"
                        >


                        {{-- TEXTO --}}
                        <div class="min-w-0 flex-1">

                            <textarea
                                wire:model="body"

                                rows="2"

                                maxlength="5000"

                                placeholder="Escribe un mensaje o adjunta un archivo..."

                                class="
                                    block
                                    max-h-32
                                    w-full
                                    resize-none
                                    rounded-xl
                                    border-gray-300
                                    text-sm
                                    shadow-sm
                                    focus:border-indigo-500
                                    focus:ring-indigo-500
                                "
                            ></textarea>


                            <x-input-error
                                for="body"
                                class="mt-1"
                            />

                        </div>


                        {{-- ENVIAR --}}
                        <button
                            type="submit"

                            wire:loading.attr="disabled"
                            wire:target="addComment"

                            x-bind:disabled="uploading"

                            class="
                                flex
                                h-11 w-11
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                bg-indigo-600
                                text-white
                                transition
                                hover:bg-indigo-500
                                disabled:cursor-not-allowed
                                disabled:opacity-50
                            "
                        >

                            <span class="sr-only">
                                Enviar mensaje
                            </span>


                            <svg
                                wire:loading.remove
                                wire:target="addComment"

                                xmlns="http://www.w3.org/2000/svg"

                                class="h-5 w-5"

                                fill="none"

                                viewBox="0 0 24 24"

                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                />
                            </svg>


                            <svg
                                wire:loading
                                wire:target="addComment"

                                class="h-5 w-5 animate-spin"

                                xmlns="http://www.w3.org/2000/svg"

                                fill="none"

                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                />

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                />
                            </svg>

                        </button>

                    </form>

                </div>


            @else

                <div class="text-center text-sm text-gray-500">

                    @if (
                        $ticket->status ===
                        \App\Enums\TicketStatus::CLOSED
                    )

                        Este ticket está cerrado.

                    @else

                        No tienes permiso para enviar mensajes.

                    @endif

                </div>

            @endcan

        </div>

    </div>

</div>