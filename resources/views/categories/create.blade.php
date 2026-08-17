<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Nueva categoría
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-xl sm:rounded-lg">

                <form
                    method="POST"
                    action="{{ route('categories.store') }}"
                    class="space-y-6"
                >
                    @csrf

                    <div>
                        <x-label for="name" value="Nombre" />

                        <x-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('name') }}"
                            required
                        />

                        <x-input-error
                            for="name"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <x-label
                            for="description"
                            value="Descripción"
                        />

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        >{{ old('description') }}</textarea>

                        <x-input-error
                            for="description"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', true))
                                class="rounded border-gray-300"
                            >

                            <span>Activa</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">

                        <a
                            href="{{ route('categories.index') }}"
                            class="rounded-md border px-4 py-2"
                        >
                            Cancelar
                        </a>

                        <x-button>
                            Guardar
                        </x-button>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>