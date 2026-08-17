<div>
    @if ($createdTicketNumber)
        <div
            class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800"
        >
            <p class="font-semibold">
                Ticket creado correctamente.
            </p>

            <p class="mt-1 text-sm">
                Número de ticket:
                <strong>{{ $createdTicketNumber }}</strong>
            </p>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- Categoría --}}
        <div>
            <x-label
                for="category_id"
                value="Categoría"
            />

            <select
                id="category_id"
                wire:model="category_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">
                    Selecciona una categoría
                </option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <x-input-error
                for="category_id"
                class="mt-2"
            />
        </div>

        {{-- Título --}}
        <div>
            <x-label
                for="title"
                value="Título"
            />

            <x-input
                id="title"
                type="text"
                wire:model="title"
                class="mt-1 block w-full"
                placeholder="Ej: No puedo iniciar sesión"
            />

            <x-input-error
                for="title"
                class="mt-2"
            />
        </div>

        {{-- Descripción --}}
        <div>
            <x-label
                for="description"
                value="Descripción del problema"
            />

            <textarea
                id="description"
                wire:model="description"
                rows="6"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Describe el problema con el mayor detalle posible..."
            ></textarea>

            <x-input-error
                for="description"
                class="mt-2"
            />
        </div>

        {{-- Prioridad --}}
        <div>
            <x-label
                for="priority"
                value="Prioridad"
            />

            <select
                id="priority"
                wire:model="priority"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                @foreach ($priorities as $priorityOption)
                    <option value="{{ $priorityOption->value }}">
                        {{ $priorityOption->label() }}
                    </option>
                @endforeach
            </select>

            <x-input-error
                for="priority"
                class="mt-2"
            />
        </div>

        <div class="flex justify-end">

            <x-button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
            >
                <span wire:loading.remove wire:target="save">
                    Crear ticket
                </span>

                <span wire:loading wire:target="save">
                    Guardando...
                </span>
            </x-button>

        </div>

    </form>
</div>