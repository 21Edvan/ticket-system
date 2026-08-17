<div
    x-data="{ open: false }"
    wire:poll.10s
    class="relative"
>

    {{-- CAMPANA --}}
    <button
        type="button"
        x-on:click="open = ! open"
        class="
            relative
            flex h-10 w-10
            items-center justify-center
            rounded-full
            text-gray-500
            transition
            hover:bg-gray-100
            hover:text-gray-800
        "
    >

        <span class="sr-only">
            Notificaciones
        </span>

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
                stroke-width="1.8"
                d="M15 17h5l-1.405-1.405A2.032
                   2.032 0 0118 14.158V11
                   a6.002 6.002 0 00-4-5.659V5
                   a2 2 0 10-4 0v.341
                   C7.67 6.165 6 8.388 6 11v3.159
                   c0 .538-.214 1.055-.595 1.436
                   L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
            />
        </svg>


        {{-- CONTADOR --}}
        @if ($unreadCount > 0)

            <span
                class="
                    absolute
                    -right-1 -top-1
                    flex min-h-5 min-w-5
                    items-center justify-center
                    rounded-full
                    bg-red-500
                    px-1
                    text-[10px]
                    font-bold
                    text-white
                    ring-2 ring-white
                "
            >
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>

        @endif

    </button>


    {{-- DROPDOWN --}}
    <div
        x-show="open"
        x-cloak
        x-on:click.outside="open = false"
        x-transition
        class="
            absolute right-0 z-50 mt-2
            w-[360px]
            max-w-[calc(100vw-2rem)]
            overflow-hidden
            rounded-xl
            border border-gray-200
            bg-white
            shadow-xl
        "
    >

        {{-- HEADER --}}
        <div
            class="
                flex items-center justify-between
                border-b border-gray-100
                px-4 py-3
            "
        >

            <div>

                <p class="font-semibold text-gray-900">
                    Notificaciones
                </p>

                @if ($unreadCount > 0)

                    <p class="text-xs text-gray-500">
                        {{ $unreadCount }}
                        {{ $unreadCount === 1 ? 'sin leer' : 'sin leer' }}
                    </p>

                @endif

            </div>


            @if ($unreadCount > 0)

                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="
                        text-xs font-semibold
                        text-indigo-600
                        hover:underline
                    "
                >
                    Marcar todas
                </button>

            @endif

        </div>


        {{-- LISTA --}}
        <div class="max-h-[420px] overflow-y-auto">

            @forelse ($notifications as $notification)

                <button
                    type="button"
                    wire:click="openNotification('{{ $notification->id }}')"
                    class="
                        relative
                        block w-full
                        border-b border-gray-100
                        px-4 py-3
                        text-left
                        transition
                        hover:bg-gray-50
                    "
                >

                    <div class="flex gap-3">

                        {{-- INDICADOR --}}
                        <div class="pt-1.5">

                            @if (is_null($notification->read_at))

                                <div
                                    class="
                                        h-2.5 w-2.5
                                        rounded-full
                                        bg-indigo-500
                                    "
                                ></div>

                            @else

                                <div
                                    class="
                                        h-2.5 w-2.5
                                        rounded-full
                                        bg-gray-200
                                    "
                                ></div>

                            @endif

                        </div>


                        <div class="min-w-0 flex-1">

                            <div
                                class="
                                    flex items-start
                                    justify-between gap-3
                                "
                            >

                                <p
                                    @class([
                                        'text-sm text-gray-900',

                                        'font-semibold' =>
                                            is_null($notification->read_at),

                                        'font-medium' =>
                                            ! is_null($notification->read_at),
                                    ])
                                >
                                    {{ $notification->data['title'] ?? 'Notificación' }}
                                </p>


                                <span
                                    class="
                                        shrink-0
                                        text-[10px]
                                        text-gray-400
                                    "
                                >
                                    {{ $notification->created_at->diffForHumans(short: true) }}
                                </span>

                            </div>


                            <p
                                class="
                                    mt-1
                                    line-clamp-2
                                    text-xs
                                    leading-5
                                    text-gray-500
                                "
                            >
                                {{ $notification->data['message'] ?? '' }}
                            </p>


                            @if (isset($notification->data['ticket_number']))

                                <p
                                    class="
                                        mt-1
                                        text-[11px]
                                        font-semibold
                                        text-indigo-600
                                    "
                                >
                                    {{ $notification->data['ticket_number'] }}
                                </p>

                            @endif

                        </div>

                    </div>

                </button>

            @empty

                <div class="px-6 py-10 text-center">

                    <p class="text-sm font-medium text-gray-700">
                        Sin notificaciones
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Los cambios importantes aparecerán aquí.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>