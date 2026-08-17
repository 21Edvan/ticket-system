@if ($ticket->directAttachments->isNotEmpty())

    <div class="mt-6 border-t border-gray-200 pt-6">

        <div class="mb-3 flex items-center justify-between">

            <div>

                <h3 class="text-sm font-semibold text-gray-900">
                    Archivos adjuntos
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $ticket->directAttachments->count() }}
                    {{ $ticket->directAttachments->count() === 1
                        ? 'archivo'
                        : 'archivos'
                    }}
                </p>

            </div>

        </div>


        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ($ticket->directAttachments as $attachment)

                <div
                    class="
                        overflow-hidden
                        rounded-lg
                        border border-gray-200
                        bg-white
                    "
                >

                    {{-- ================================================
                        IMAGEN
                    ================================================ --}}
                    @if ($attachment->isImage())

                        <a
                            href="{{ route(
                                'attachments.show',
                                $attachment
                            ) }}"
                            target="_blank"
                            class="block h-40 bg-gray-100"
                        >

                            <img
                                src="{{ route(
                                    'attachments.show',
                                    $attachment
                                ) }}"
                                alt="{{ $attachment->original_name }}"
                                class="
                                    h-full w-full
                                    object-cover
                                    transition
                                    hover:opacity-90
                                "
                                loading="lazy"
                            >

                        </a>

                    @else

                        {{-- ============================================
                            ARCHIVO GENÉRICO
                        ============================================ --}}
                        <div
                            class="
                                flex h-28
                                items-center justify-center
                                bg-gray-50
                            "
                        >

                            <div
                                class="
                                    flex h-12 w-12
                                    items-center justify-center
                                    rounded-lg
                                    bg-indigo-100
                                    text-indigo-600
                                "
                            >

                                <svg
                                    class="h-6 w-6"
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

                        </div>

                    @endif


                    {{-- ================================================
                        INFORMACIÓN
                    ================================================ --}}
                    <div class="p-3">

                        <p
                            class="
                                truncate
                                text-sm
                                font-semibold
                                text-gray-800
                            "
                            title="{{ $attachment->original_name }}"
                        >
                            {{ $attachment->original_name }}
                        </p>


                        <div
                            class="
                                mt-1
                                flex items-center
                                justify-between
                                gap-2
                            "
                        >

                            <span class="text-xs text-gray-500">
                                {{ $attachment->formattedSize() }}
                            </span>


                            @if ($attachment->uploader)

                                <span
                                    class="
                                        max-w-[120px]
                                        truncate
                                        text-xs
                                        text-gray-400
                                    "
                                    title="{{ $attachment->uploader->name }}"
                                >
                                    {{ $attachment->uploader->name }}
                                </span>

                            @endif

                        </div>


                        <div class="mt-3 flex gap-2">

                            {{-- ABRIR --}}
                            <a
                                href="{{ route(
                                    'attachments.show',
                                    $attachment
                                ) }}"
                                target="_blank"
                                class="
                                    flex-1
                                    rounded-md
                                    border border-gray-300
                                    bg-white
                                    px-2.5 py-1.5
                                    text-center
                                    text-xs
                                    font-semibold
                                    text-gray-700
                                    transition
                                    hover:bg-gray-50
                                "
                            >
                                Abrir
                            </a>


                            {{-- DESCARGAR --}}
                            <a
                                href="{{ route(
                                    'attachments.download',
                                    $attachment
                                ) }}"
                                class="
                                    flex-1
                                    rounded-md
                                    bg-indigo-600
                                    px-2.5 py-1.5
                                    text-center
                                    text-xs
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-indigo-500
                                "
                            >
                                Descargar
                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endif