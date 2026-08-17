<div>

    {{-- Filtros --}}
    <div class="mb-6 grid gap-4 md:grid-cols-2 lg:grid-cols-5">

        {{-- Buscar --}}
        <div class="lg:col-span-2">
            <x-label
                for="search"
                value="Buscar"
            />

            <x-input
                id="search"
                type="text"
                wire:model.live.debounce.300ms="search"
                class="mt-1 block w-full"
                placeholder="Número o título..."
            />
        </div>

        {{-- Estado --}}
        <div>
            <x-label
                for="status"
                value="Estado"
            />

            <select
                id="status"
                wire:model.live="status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">
                    Todos
                </option>

                @foreach ($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}">
                        {{ $statusOption->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Prioridad --}}
        <div>
            <x-label
                for="priority"
                value="Prioridad"
            />

            <select
                id="priority"
                wire:model.live="priority"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">
                    Todas
                </option>

                @foreach ($priorities as $priorityOption)
                    <option value="{{ $priorityOption->value }}">
                        {{ $priorityOption->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Categoría --}}
        <div>
            <x-label
                for="category"
                value="Categoría"
            />

            <select
                id="category"
                wire:model.live="category"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">
                    Todas
                </option>

                @foreach ($categories as $categoryOption)
                    <option value="{{ $categoryOption->id }}">
                        {{ $categoryOption->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- Acciones --}}
    <div class="mb-4 flex items-center justify-between">

        <button
            wire:click="clearFilters"
            type="button"
            class="text-sm text-gray-600 hover:text-gray-900 hover:underline"
        >
            Limpiar filtros
        </button>

        <a
            href="{{ route('tickets.create') }}"
            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
        >
            Nuevo ticket
        </a>

    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-lg border bg-white">

        <table class="w-full">

            <thead class="bg-gray-50">
                <tr class="border-b text-left text-sm text-gray-600">

                    <th class="px-4 py-3">
                        Ticket
                    </th>

                    <th class="px-4 py-3">
                        Título
                    </th>

                    <th class="px-4 py-3">
                        Categoría
                    </th>

                    <th class="px-4 py-3">
                        Prioridad
                    </th>

                    <th class="px-4 py-3">
                        Estado
                    </th>

                    <th class="px-4 py-3">
                        Fecha
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse ($tickets as $ticket)

                    <tr class="border-b text-sm">

                        <td class="whitespace-nowrap px-4 py-4 font-medium">
                           <a
                                href="{{ route('tickets.show', $ticket) }}"
                                class="font-semibold text-indigo-600 hover:underline"
                            >
                                {{ $ticket->ticket_number }}
                            </a>
                        </td>

                        <td class="px-4 py-4">
                            {{ $ticket->title }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $ticket->category->name }}
                        </td>

                        <td class="px-4 py-4">

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

                        </td>

                        <td class="px-4 py-4">

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

                        </td>

                        <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="6"
                            class="px-4 py-12 text-center text-gray-500"
                        >
                            No se encontraron tickets.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Paginación --}}
    <div class="mt-6">
        {{ $tickets->links() }}
    </div>

</div>