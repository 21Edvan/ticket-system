<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Categorías
            </h2>

            <a
                href="{{ route('categories.create') }}"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                Nueva categoría
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <table class="w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="px-4 py-3">Nombre</th>
                                <th class="px-4 py-3">Descripción</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $category)
                                <tr class="border-b ">
                                    <td class="px-4 py-3">
                                        {{ $category->name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $category->description ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        @if ($category->is_active)
                                            <span class="text-green-600">
                                                Activa
                                            </span>
                                        @else
                                            <span class="text-red-600">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex gap-3">

                                            <a
                                                href="{{ route('categories.edit', $category) }}"
                                                class="text-indigo-600 hover:underline"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('categories.destroy', $category) }}"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:underline"
                                                    onclick="return confirm('¿Eliminar esta categoría?')"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-4 py-8 text-center text-gray-500"
                                    >
                                        No hay categorías registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>