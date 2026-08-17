<div>

    {{-- ============================================================
        CABECERA
    ============================================================ --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Usuarios
            </h1>

            <p class="mt-0.5 text-sm text-gray-500">
                Gestiona los roles de usuarios y técnicos.
            </p>
        </div>

    </div>


    {{-- ============================================================
        RESUMEN
    ============================================================ --}}
    <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">

        <div class="rounded-lg border bg-white px-4 py-3 shadow-sm">
            <p class="text-[11px] font-semibold uppercase text-gray-500">
                Total
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-900">
                {{ $stats['total'] }}
            </p>
        </div>


        <div class="rounded-lg border bg-white px-4 py-3 shadow-sm">
            <p class="text-[11px] font-semibold uppercase text-gray-500">
                Administradores
            </p>

            <p class="mt-1 text-2xl font-bold text-purple-600">
                {{ $stats['admins'] }}
            </p>
        </div>


        <div class="rounded-lg border bg-white px-4 py-3 shadow-sm">
            <p class="text-[11px] font-semibold uppercase text-gray-500">
                Técnicos
            </p>

            <p class="mt-1 text-2xl font-bold text-blue-600">
                {{ $stats['technicians'] }}
            </p>
        </div>


        <div class="rounded-lg border bg-white px-4 py-3 shadow-sm">
            <p class="text-[11px] font-semibold uppercase text-gray-500">
                Usuarios
            </p>

            <p class="mt-1 text-2xl font-bold text-gray-700">
                {{ $stats['users'] }}
            </p>
        </div>

    </div>


    {{-- ============================================================
        MENSAJES
    ============================================================ --}}
    @if (session('success'))

        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- ============================================================
        EDICIÓN DE ROL
    ============================================================ --}}
    @if ($editingUserId)

        @php
            $editingUser = \App\Models\User::find(
                $editingUserId
            );
        @endphp

        <div class="mb-5 rounded-lg border border-indigo-200 bg-indigo-50 p-4">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">
                        Cambiar rol
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $editingUser?->name }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $editingUser?->email }}
                    </p>

                </div>


                <form
                    wire:submit="updateRole"
                    class="flex flex-col gap-3 sm:flex-row sm:items-end"
                >

                    <div>

                        <label
                            for="role"
                            class="block text-xs font-semibold text-gray-600"
                        >
                            Nuevo rol
                        </label>

                        <select
                            id="role"
                            wire:model="role"
                            class="mt-1 min-w-48 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                            @foreach ($roles as $roleOption)

                                <option value="{{ $roleOption->value }}">

                                    @switch($roleOption)

                                        @case(\App\Enums\UserRole::ADMIN)
                                            Administrador
                                            @break

                                        @case(\App\Enums\UserRole::TECHNICIAN)
                                            Técnico
                                            @break

                                        @default
                                            Usuario

                                    @endswitch

                                </option>

                            @endforeach

                        </select>

                        <x-input-error
                            for="role"
                            class="mt-2"
                        />

                    </div>


                    <div class="flex gap-2">

                        <button
                            type="button"
                            wire:click="cancelEdit"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="updateRole"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                        >

                            <span
                                wire:loading.remove
                                wire:target="updateRole"
                            >
                                Guardar
                            </span>

                            <span
                                wire:loading
                                wire:target="updateRole"
                            >
                                Guardando...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


    {{-- ============================================================
        TABLA
    ============================================================ --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">

        {{-- FILTROS --}}
        <div class="flex flex-col gap-3 border-b bg-gray-50 px-4 py-3 lg:flex-row lg:items-end">

            <div class="flex-1">

                <label class="text-xs font-semibold uppercase text-gray-500">
                    Buscar
                </label>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre o correo..."
                    class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

            </div>


            <div class="lg:w-56">

                <label class="text-xs font-semibold uppercase text-gray-500">
                    Rol
                </label>

                <select
                    wire:model.live="roleFilter"
                    class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        Todos
                    </option>

                    <option value="admin">
                        Administradores
                    </option>

                    <option value="technician">
                        Técnicos
                    </option>

                    <option value="user">
                        Usuarios
                    </option>

                </select>

            </div>


            <button
                type="button"
                wire:click="clearFilters"
                class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Limpiar
            </button>

        </div>


        {{-- TABLA --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">

                        <th class="px-4 py-3">
                            Usuario
                        </th>

                        <th class="px-4 py-3">
                            Rol
                        </th>

                        <th class="px-4 py-3">
                            Tickets creados
                        </th>

                        <th class="px-4 py-3">
                            Asignados
                        </th>

                        <th class="px-4 py-3">
                            Registro
                        </th>

                        <th class="px-4 py-3 text-right">
                            Acción
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($users as $item)

                        <tr class="text-sm hover:bg-gray-50">

                            {{-- USUARIO --}}
                            <td class="px-4 py-3">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 font-semibold text-gray-700">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>


                                    <div>

                                        <div class="flex items-center gap-2">

                                            <p class="font-semibold text-gray-900">
                                                {{ $item->name }}
                                            </p>

                                            @if ($item->id === auth()->id())

                                                <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold text-gray-500">
                                                    Tú
                                                </span>

                                            @endif

                                        </div>

                                        <p class="text-xs text-gray-500">
                                            {{ $item->email }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- ROL --}}
                            <td class="whitespace-nowrap px-4 py-3">

                                @if ($item->isAdmin())

                                    <span class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700">
                                        Administrador
                                    </span>

                                @elseif ($item->isTechnician())

                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        Técnico
                                    </span>

                                @else

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                        Usuario
                                    </span>

                                @endif

                            </td>


                            {{-- CREADOS --}}
                            <td class="px-4 py-3">

                                <span class="font-semibold text-gray-800">
                                    {{ $item->tickets_count }}
                                </span>

                            </td>


                            {{-- ASIGNADOS --}}
                            <td class="px-4 py-3">

                                @if ($item->isTechnician())

                                    <span class="font-semibold text-gray-800">
                                        {{ $item->assigned_tickets_count }}
                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- REGISTRO --}}
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-500">

                                {{ $item->created_at->format('d/m/Y') }}

                            </td>


                            {{-- ACCIÓN --}}
                            <td class="whitespace-nowrap px-4 py-3 text-right">

                                @if ($item->id !== auth()->id())

                                    <button
                                        type="button"
                                        wire:click="editRole({{ $item->id }})"
                                        class="text-sm font-semibold text-indigo-600 hover:underline"
                                    >
                                        Cambiar rol
                                    </button>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Cuenta actual
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-4 py-12 text-center text-sm text-gray-500"
                            >
                                No se encontraron usuarios.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINACIÓN --}}
        @if ($users->hasPages())

            <div class="border-t px-4 py-3">

                {{ $users->links() }}

            </div>

        @endif

    </div>

</div>