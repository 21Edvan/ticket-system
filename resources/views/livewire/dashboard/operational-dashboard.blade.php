<div wire:poll.30s.visible class="ticket-dashboard">

    <style>
        .ticket-dashboard {
            width: 100%;
        }

        .ticket-dashboard-metrics {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .ticket-dashboard-main {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
            gap: 1.25rem;
            align-items: start;
        }

        .ticket-dashboard-side {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        @media (max-width: 1100px) {
            .ticket-dashboard-metrics {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .ticket-dashboard-main {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .ticket-dashboard-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>


    {{-- ============================================================
        ENCABEZADO
    ============================================================ --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">

                @if ($user->isAdmin())
                    Centro de soporte
                @elseif ($user->isTechnician())
                    Mi panel de soporte
                @else
                    Mis solicitudes
                @endif

            </h1>

            <p class="mt-0.5 text-sm text-gray-500">

                @if ($user->isAdmin())
                    Resumen operativo del sistema
                @elseif ($user->isTechnician())
                    Tickets asignados y pendientes
                @else
                    Estado de tus solicitudes de soporte
                @endif

            </p>
        </div>


        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('tickets.index') }}"
                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                Ver tickets
            </a>

            @if ($user->isUser())

                <a
                    href="{{ route('tickets.create') }}"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-500"
                >
                    Nuevo ticket
                </a>

            @endif

        </div>

    </div>


    {{-- ============================================================
        MÉTRICAS
    ============================================================ --}}
    <div class="ticket-dashboard-metrics">

        {{-- TOTAL --}}
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">

                        @if ($user->isAdmin())
                            Total
                        @elseif ($user->isTechnician())
                            Asignados
                        @else
                            Mis tickets
                        @endif

                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $stats['total'] }}
                    </p>
                </div>

                <div class="rounded-lg bg-gray-100 p-2 text-gray-500">

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- ACTIVOS --}}
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                Activos
            </p>

            <p class="mt-1 text-2xl font-bold text-indigo-600">
                {{ $stats['active'] }}
            </p>

        </div>


        {{-- CRÍTICOS --}}
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                        Críticos
                    </p>

                    <p class="mt-1 text-2xl font-bold text-red-600">
                        {{ $stats['critical'] }}
                    </p>
                </div>


                @if ($stats['critical'] > 0)

                    <span class="rounded-full bg-red-100 px-2 py-1 text-[10px] font-bold uppercase text-red-700">
                        Atención
                    </span>

                @endif

            </div>

        </div>


        {{-- EN PROCESO --}}
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                En proceso
            </p>

            <p class="mt-1 text-2xl font-bold text-yellow-600">
                {{ $stats['in_progress'] }}
            </p>

        </div>


        {{-- EN ESPERA --}}
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                En espera
            </p>

            <p class="mt-1 text-2xl font-bold text-orange-600">
                {{ $stats['waiting'] }}
            </p>

        </div>


        {{-- RESUELTOS --}}
        <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">

            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                Resueltos
            </p>

            <p class="mt-1 text-2xl font-bold text-green-600">
                {{ $stats['resolved'] }}
            </p>

        </div>

    </div>


    {{-- ============================================================
        CONTENIDO PRINCIPAL
    ============================================================ --}}
    <div class="ticket-dashboard-main mt-4">


        {{-- ========================================================
            TICKETS RECIENTES
        ======================================================== --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">

                <div>

                    <h2 class="text-sm font-semibold text-gray-900">
                        Tickets recientes
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Últimos tickets dentro de tu alcance
                    </p>

                </div>


                <a
                    href="{{ route('tickets.index') }}"
                    class="text-xs font-semibold text-indigo-600 hover:underline"
                >
                    Ver todos
                </a>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50">

                        <tr class="text-left text-[10px] font-bold uppercase tracking-wide text-gray-500">

                            <th class="px-4 py-2">
                                Ticket
                            </th>

                            @if ($user->isAdmin() || $user->isTechnician())

                                <th class="px-4 py-2">
                                    Usuario
                                </th>

                            @endif

                            <th class="px-4 py-2">
                                Categoría
                            </th>

                            <th class="px-4 py-2">
                                Estado
                            </th>

                            <th class="px-4 py-2">
                                Prioridad
                            </th>

                            <th class="px-4 py-2">
                                Fecha
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse ($recentTickets as $ticket)

                            <tr class="text-sm transition hover:bg-gray-50">

                                {{-- TICKET --}}
                                <td class="px-4 py-3">

                                    <a
                                        href="{{ route('tickets.show', $ticket) }}"
                                        class="font-semibold text-indigo-600 hover:underline"
                                    >
                                        {{ $ticket->ticket_number }}
                                    </a>

                                    <p
                                        class="mt-0.5 max-w-[240px] truncate text-xs text-gray-500"
                                        title="{{ $ticket->title }}"
                                    >
                                        {{ $ticket->title }}
                                    </p>

                                </td>


                                {{-- USUARIO --}}
                                @if ($user->isAdmin() || $user->isTechnician())

                                    <td class="whitespace-nowrap px-4 py-3">

                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $ticket->user->name }}
                                        </p>

                                    </td>

                                @endif


                                {{-- CATEGORÍA --}}
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">

                                    {{ $ticket->category->name }}

                                </td>


                                {{-- ESTADO --}}
                                <td class="whitespace-nowrap px-4 py-3">

                                    <span @class([
                                        'rounded-full px-2 py-1 text-[11px] font-semibold',

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

                                </td>


                                {{-- PRIORIDAD --}}
                                <td class="whitespace-nowrap px-4 py-3">

                                    <span @class([
                                        'text-xs font-semibold',

                                        'text-gray-500' =>
                                            $ticket->priority === \App\Enums\TicketPriority::LOW,

                                        'text-blue-600' =>
                                            $ticket->priority === \App\Enums\TicketPriority::MEDIUM,

                                        'text-orange-600' =>
                                            $ticket->priority === \App\Enums\TicketPriority::HIGH,

                                        'text-red-600' =>
                                            $ticket->priority === \App\Enums\TicketPriority::CRITICAL,
                                    ])>
                                        {{ $ticket->priority->label() }}
                                    </span>

                                </td>


                                {{-- FECHA --}}
                                <td class="whitespace-nowrap px-4 py-3">

                                    <p class="text-xs text-gray-500">
                                        {{ $ticket->created_at->format('d/m/Y') }}
                                    </p>

                                    <p class="text-[10px] text-gray-400">
                                        {{ $ticket->created_at->format('H:i') }}
                                    </p>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="{{ $user->isAdmin() || $user->isTechnician() ? 6 : 5 }}"
                                    class="px-4 py-12 text-center text-sm text-gray-500"
                                >
                                    No hay tickets disponibles.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================
            PANEL DERECHO ADMIN
        ======================================================== --}}
        @if ($user->isAdmin())

            <div class="ticket-dashboard-side">


                {{-- ====================================================
                    SIN ASIGNAR
                ==================================================== --}}
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">

                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">

                        <div>

                            <h2 class="text-sm font-semibold text-gray-900">
                                Sin asignar
                            </h2>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Tickets esperando técnico
                            </p>

                        </div>


                        @if ($unassignedTickets->isNotEmpty())

                            <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-700">
                                {{ $unassignedTickets->count() }}
                            </span>

                        @else

                            <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">
                                0
                            </span>

                        @endif

                    </div>


                    <div class="divide-y divide-gray-100">

                        @forelse ($unassignedTickets as $ticket)

                            <a
                                href="{{ route('tickets.show', $ticket) }}"
                                class="block px-4 py-3 transition hover:bg-gray-50"
                            >

                                <div class="flex items-start justify-between gap-3">

                                    <div class="min-w-0">

                                        <p class="truncate text-xs font-semibold text-indigo-600">
                                            {{ $ticket->ticket_number }}
                                        </p>

                                        <p class="mt-0.5 truncate text-sm font-medium text-gray-800">
                                            {{ $ticket->title }}
                                        </p>

                                        <p class="mt-1 truncate text-xs text-gray-500">
                                            {{ $ticket->user->name }}
                                            ·
                                            {{ $ticket->category->name }}
                                        </p>

                                    </div>


                                    <div class="shrink-0 text-right">

                                        <span @class([
                                            'text-xs font-semibold',

                                            'text-gray-500' =>
                                                $ticket->priority === \App\Enums\TicketPriority::LOW,

                                            'text-blue-600' =>
                                                $ticket->priority === \App\Enums\TicketPriority::MEDIUM,

                                            'text-orange-600' =>
                                                $ticket->priority === \App\Enums\TicketPriority::HIGH,

                                            'text-red-600' =>
                                                $ticket->priority === \App\Enums\TicketPriority::CRITICAL,
                                        ])>
                                            {{ $ticket->priority->label() }}
                                        </span>

                                        <p class="mt-1 text-[10px] text-gray-400">
                                            {{ $ticket->created_at->diffForHumans() }}
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @empty

                            <div class="px-4 py-8 text-center">

                                <p class="text-sm font-semibold text-gray-700">
                                    Todo asignado
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    No hay tickets esperando técnico.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- ====================================================
                    CARGA DE TÉCNICOS
                ==================================================== --}}
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-4 py-3">

                        <h2 class="text-sm font-semibold text-gray-900">
                            Carga de técnicos
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Tickets activos actualmente
                        </p>

                    </div>


                    <div class="divide-y divide-gray-100">

                        @forelse ($technicians as $technician)

                            <div class="px-4 py-3">

                                <div class="flex items-center justify-between gap-4">

                                    <div class="flex min-w-0 items-center gap-3">

                                        {{-- AVATAR --}}
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                            {{ strtoupper(substr($technician->name, 0, 1)) }}
                                        </div>


                                        <div class="min-w-0">

                                            <p class="truncate text-sm font-semibold text-gray-900">
                                                {{ $technician->name }}
                                            </p>


                                            @if ($technician->critical_tickets_count > 0)

                                                <p class="text-[11px] font-medium text-red-600">
                                                    {{ $technician->critical_tickets_count }}
                                                    {{ $technician->critical_tickets_count === 1 ? 'crítico' : 'críticos' }}
                                                </p>

                                            @else

                                                <p class="text-[11px] text-gray-400">
                                                    Sin críticos
                                                </p>

                                            @endif

                                        </div>

                                    </div>


                                    <div class="shrink-0 text-right">

                                        <span class="text-lg font-bold text-gray-900">
                                            {{ $technician->active_tickets_count }}
                                        </span>

                                        <p class="text-[10px] text-gray-500">
                                            activos
                                        </p>

                                    </div>

                                </div>


                                @php
                                    $load = min(
                                        $technician->active_tickets_count * 10,
                                        100
                                    );
                                @endphp


                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-gray-100">

                                    <div
                                        class="h-full rounded-full bg-indigo-500 transition-all"
                                        style="width: {{ $load }}%"
                                    ></div>

                                </div>

                            </div>

                        @empty

                            <div class="px-4 py-8 text-center">

                                <p class="text-sm text-gray-500">
                                    No hay técnicos registrados.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>