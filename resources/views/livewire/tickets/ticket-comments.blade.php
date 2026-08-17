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

    {{-- Botón flotante --}}
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
            flex items-center gap-3
            rounded-full
            bg-indigo-600
            px-5 py-4
            font-semibold text-white
            shadow-xl
            transition
            hover:bg-indigo-500
        "
    >

        {{-- Icono --}}
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
            @if ($unreadCount > 0)

                <span
                    class="
                        flex
                        min-w-6
                        items-center
                        justify-center
                        rounded-full
                        bg-red-500
                        px-2
                        py-1
                        text-xs
                        font-bold
                        text-white
                    "
                >
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>

            @endif
        </span>

    </button>


    {{-- Panel de chat --}}
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
            h-[80vh]
            max-h-[650px]
            flex-col
            overflow-hidden
            rounded-2xl
            bg-white
            shadow-2xl
            ring-1 ring-gray-200

            sm:bottom-6
            sm:left-auto
            sm:right-6
            sm:h-[620px]
            sm:w-[400px]
        "
    >

        {{-- Header --}}
        <div
            class="
                flex
                items-center
                justify-between
                border-b
                bg-white
                px-5 py-4
            "
        >

            <div>

                <p class="font-semibold text-gray-900">
                    Soporte
                </p>

                <p class="text-xs text-gray-500">
                    {{ $ticket->ticket_number }}
                    ·
                    {{ $ticket->status->label() }}
                </p>

            </div>

            <button
                type="button"
                x-on:click="open = false"
                class="
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


        {{-- Técnico --}}
        <div class="border-b bg-gray-50 px-5 py-3">

            @if ($ticket->technician)

                <p class="text-sm font-medium text-gray-800">
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


        {{-- Mensajes --}}
        <div
            x-ref="messages"
            class="
                flex-1
                space-y-4
                overflow-y-auto
                bg-gray-50
                p-4
            "
        >

            @forelse ($comments as $comment)

                @php
                    $isMine = $comment->user_id === auth()->id();
                @endphp

                <div
                    wire:key="comment-{{ $comment->id }}"
                    class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}"
                >

                    <div class="max-w-[85%]">

                        <div
                            class="
                                mb-1
                                flex
                                items-center
                                gap-2
                                text-xs
                                {{ $isMine ? 'justify-end' : 'justify-start' }}
                            "
                        >

                            <span class="font-medium text-gray-700">
                                {{ $comment->user->name }}
                            </span>

                            @if ($comment->user->isAdmin())

                                <span class="text-purple-600">
                                    Administrador
                                </span>

                            @elseif ($comment->user->isTechnician())

                                <span class="text-blue-600">
                                    Técnico
                                </span>

                            @else

                                <span class="text-gray-500">
                                    Usuario
                                </span>

                            @endif

                        </div>


                        <div
                            @class([
                                'rounded-2xl px-4 py-3 text-sm shadow-sm',

                                'rounded-br-md bg-indigo-600 text-white'
                                    => $isMine,

                                'rounded-bl-md border bg-white text-gray-800'
                                    => ! $isMine,
                            ])
                        >
                            {!! nl2br(e($comment->body)) !!}
                        </div>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                                {{ $isMine ? 'text-right' : 'text-left' }}
                            "
                        >
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </p>

                    </div>

                </div>

            @empty

                <div
                    class="
                        flex
                        h-full
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


        {{-- Formulario --}}
        <div class="border-t bg-white p-4">

            @can('comment', $ticket)

                <form
                    wire:submit="addComment"
                    class="flex items-end gap-2"
                >

                    <div class="flex-1">

                        <textarea
                            wire:model="body"
                            rows="2"
                            placeholder="Escribe tu mensaje..."
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


                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="addComment"
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