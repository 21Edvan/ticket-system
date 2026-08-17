<x-app-layout>

    <x-slot name="header">

        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            @if (auth()->user()->isAdmin())
                Todos los tickets
            @elseif (auth()->user()->isTechnician())
                Tickets asignados
            @else
                Mis tickets
            @endif
        </h2>

    </x-slot>

    <div class="py-12">

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">

                <div class="p-6">

                    <livewire:tickets.list-tickets />

                </div>

            </div>

        </div>

    </div>

</x-app-layout>