<div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

    <div class="border-b px-6 py-4">

        <h3 class="text-lg font-semibold text-gray-900">
            Actividad
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Historial de cambios realizados en el ticket.
        </p>

    </div>


    <div class="p-6">

        @forelse ($histories as $history)

            <div class="relative flex gap-4 pb-8 last:pb-0">

                {{-- Línea --}}
                @if (! $loop->last)

                    <div
                        class="absolute left-[7px] top-5 h-full w-px bg-gray-200"
                    ></div>

                @endif


                {{-- Punto --}}
                <div class="relative z-10 mt-2">

                    <div
                        class="h-4 w-4 rounded-full border-4 border-white bg-indigo-500 ring-1 ring-gray-200"
                    ></div>

                </div>


                {{-- Contenido --}}
                <div class="min-w-0 flex-1">

                    <p class="font-medium text-gray-900">
                        {{ $history->description() }}
                    </p>


                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">

                        <span>
                            {{ $history->actor?->name ?? 'Sistema' }}
                        </span>

                        <span>
                            ·
                        </span>

                        <span>
                            {{ $history->created_at->format('d/m/Y H:i') }}
                        </span>

                    </div>

                </div>

            </div>

        @empty

            <div class="py-6 text-center text-sm text-gray-500">
                Todavía no hay actividad registrada.
            </div>

        @endforelse

    </div>

</div>