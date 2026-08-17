<div>

    @if (session('assignment_success'))
        <div class="mb-4 rounded-md bg-green-100 p-4 text-green-700">
            {{ session('assignment_success') }}
        </div>
    @endif

    <div class="rounded-lg border bg-gray-50 p-5">

        <h3 class="text-lg font-semibold text-gray-900">
            Asignación
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Selecciona el técnico responsable de este ticket.
        </p>

        <form
            wire:submit="assign"
            class="mt-5"
        >

            <div>
                <x-label
                    for="technician_id"
                    value="Técnico"
                />

                <select
                    id="technician_id"
                    wire:model="technician_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        Selecciona un técnico
                    </option>

                    @foreach ($technicians as $technician)
                        <option value="{{ $technician->id }}">
                            {{ $technician->name }}
                            — {{ $technician->email }}
                        </option>
                    @endforeach

                </select>

                <x-input-error
                    for="technician_id"
                    class="mt-2"
                />
            </div>

            <div class="mt-4">

                <x-button
                    type="submit"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>
                        Asignar técnico
                    </span>

                    <span wire:loading>
                        Asignando...
                    </span>
                </x-button>

            </div>

        </form>

    </div>

</div>