<div>

    @if (session('status_success'))
        <div class="mb-4 rounded-md bg-green-100 p-4 text-green-700">
            {{ session('status_success') }}
        </div>
    @endif

    <div class="rounded-lg border bg-gray-50 p-5">

        <h3 class="text-lg font-semibold text-gray-900">
            Estado del ticket
        </h3>

        <div class="mt-4">

            <p class="text-sm text-gray-500">
                Estado actual
            </p>

            <p class="mt-1 font-semibold">
                {{ $ticket->status->label() }}
            </p>

        </div>

        @if (count($availableStatuses) > 0)

            <form
                wire:submit="updateStatus"
                class="mt-5"
            >

                <div>
                    <x-label
                        for="status"
                        value="Cambiar a"
                    />

                    <select
                        id="status"
                        wire:model="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Selecciona un estado
                        </option>

                        @foreach ($availableStatuses as $statusOption)

                            <option value="{{ $statusOption->value }}">
                                {{ $statusOption->label() }}
                            </option>

                        @endforeach

                    </select>

                    <x-input-error
                        for="status"
                        class="mt-2"
                    />
                </div>

                <div class="mt-4">

                    <x-button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="updateStatus"
                    >

                        <span
                            wire:loading.remove
                            wire:target="updateStatus"
                        >
                            Actualizar estado
                        </span>

                        <span
                            wire:loading
                            wire:target="updateStatus"
                        >
                            Actualizando...
                        </span>

                    </x-button>

                </div>

            </form>

        @else

            <p class="mt-4 text-sm text-gray-500">
                Este ticket no tiene más cambios de estado disponibles.
            </p>

        @endif

    </div>

</div>