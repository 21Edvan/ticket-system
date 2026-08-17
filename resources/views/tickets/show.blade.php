<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $ticket->ticket_number }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $ticket->title }}
                </p>
            </div>

            <a
                href="{{ route('tickets.index') }}"
                class="text-sm text-gray-600 hover:text-gray-900 hover:underline"
            >
                Volver a tickets
            </a>

        </div>
    </x-slot>


    <div class="py-12">

        <div class="mx-auto max-w-5xl space-y-8 sm:px-6 lg:px-8">

            {{-- Información del ticket --}}
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

                <div class="p-6">

                    {{-- Información principal --}}
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">

                        {{-- Categoría --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Categoría
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $ticket->category->name }}
                            </p>
                        </div>


                        {{-- Prioridad --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Prioridad
                            </p>

                            <p class="mt-1">

                                <span @class([
                                    'rounded-full px-3 py-1 text-xs font-semibold',

                                    'bg-gray-100 text-gray-700' =>
                                        $ticket->priority === \App\Enums\TicketPriority::LOW,

                                    'bg-blue-100 text-blue-700' =>
                                        $ticket->priority === \App\Enums\TicketPriority::MEDIUM,

                                    'bg-orange-100 text-orange-700' =>
                                        $ticket->priority === \App\Enums\TicketPriority::HIGH,

                                    'bg-red-100 text-red-700' =>
                                        $ticket->priority === \App\Enums\TicketPriority::CRITICAL,
                                ])>
                                    {{ $ticket->priority->label() }}
                                </span>

                            </p>
                        </div>


                        {{-- Estado --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Estado
                            </p>

                            <p class="mt-1">

                                <span @class([
                                    'rounded-full px-3 py-1 text-xs font-semibold',

                                    'bg-blue-100 text-blue-700' =>
                                        $ticket->status === \App\Enums\TicketStatus::OPEN,

                                    'bg-indigo-100 text-indigo-700' =>
                                        $ticket->status === \App\Enums\TicketStatus::ASSIGNED,

                                    'bg-yellow-100 text-yellow-700' =>
                                        $ticket->status === \App\Enums\TicketStatus::IN_PROGRESS,

                                    'bg-orange-100 text-orange-700' =>
                                        $ticket->status === \App\Enums\TicketStatus::WAITING,

                                    'bg-green-100 text-green-700' =>
                                        $ticket->status === \App\Enums\TicketStatus::RESOLVED,

                                    'bg-gray-100 text-gray-700' =>
                                        $ticket->status === \App\Enums\TicketStatus::CLOSED,
                                ])>
                                    {{ $ticket->status->label() }}
                                </span>

                            </p>
                        </div>


                        {{-- Fecha --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Creado
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                    </div>

                    
                    <hr class="my-8">


                    {{-- Descripción --}}
                    <div>

                        <h3 class="text-lg font-semibold text-gray-900">
                            Descripción
                        </h3>

                        <div class="mt-4 rounded-lg bg-gray-50 p-5 text-gray-700">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>

                    </div>


                    <hr class="my-8">


                    {{-- Personas --}}
                    <div class="grid gap-6 md:grid-cols-2">

                        {{-- Reportado por --}}
                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Reportado por
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $ticket->user->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $ticket->user->email }}
                            </p>

                        </div>


                        {{-- Técnico --}}
                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Técnico asignado
                            </p>

                            @if ($ticket->technician)

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $ticket->technician->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $ticket->technician->email }}
                                </p>

                            @else

                                <p class="mt-1 text-gray-500">
                                    Sin técnico asignado
                                </p>

                            @endif

                        </div>

                    </div>

                </div>
                <livewire:tickets.ticket-activity
                    :ticket="$ticket"
                    :key="'activity-'.$ticket->id"
                />

                @include(
                    'tickets.partials.attachments',
                    ['ticket' => $ticket]
                )

            </div>


            {{-- Administración del ticket --}}
            @if (auth()->user()->isAdmin())

                <livewire:tickets.assign-technician
                    :ticket="$ticket"
                    :key="'assign-'.$ticket->id"
                />

            @endif


            {{-- Cambio de estado --}}
            @if (
                auth()->user()->isAdmin()
                || (
                    auth()->user()->isTechnician()
                    && $ticket->assigned_to === auth()->id()
                )
            )

                <livewire:tickets.change-ticket-status
                    :ticket="$ticket"
                    :key="'status-'.$ticket->id"
                />

            @endif

        </div>

    </div>


    {{-- Chat flotante --}}
    <livewire:tickets.ticket-comments
        :ticket="$ticket"
        :key="'comments-'.$ticket->id"
    />

</x-app-layout>