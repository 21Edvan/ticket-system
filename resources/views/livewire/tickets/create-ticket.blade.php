<div>

    <form
        wire:submit="save"
        class="space-y-6"
    >

        {{-- ============================================================
            CATEGORÍA
        ============================================================ --}}
        <div>

            <label
                for="category_id"
                class="block text-sm font-medium text-gray-700"
            >
                Categoría
            </label>

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


        {{-- ============================================================
            TÍTULO
        ============================================================ --}}
        <div>

            <label
                for="title"
                class="block text-sm font-medium text-gray-700"
            >
                Título
            </label>

            <input
                id="title"
                type="text"
                wire:model="title"
                maxlength="150"
                placeholder="Describe brevemente el problema"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >

            <x-input-error
                for="title"
                class="mt-2"
            />

        </div>


        {{-- ============================================================
            PRIORIDAD
        ============================================================ --}}
        <div>

            <label
                for="priority"
                class="block text-sm font-medium text-gray-700"
            >
                Prioridad
            </label>

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


        {{-- ============================================================
            DESCRIPCIÓN
        ============================================================ --}}
        <div>

            <label
                for="description"
                class="block text-sm font-medium text-gray-700"
            >
                Descripción
            </label>

            <textarea
                id="description"
                wire:model="description"
                rows="6"
                maxlength="5000"
                placeholder="Explica el problema con el mayor detalle posible..."
                class="mt-1 block w-full resize-y rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>

            <x-input-error
                for="description"
                class="mt-2"
            />

        </div>


        {{-- ============================================================
            ADJUNTOS
        ============================================================ --}}
        <div>

            <div class="mb-2 flex items-end justify-between gap-3">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Archivos adjuntos
                    </label>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Puedes agregar hasta 5 archivos de máximo 10 MB cada uno.
                    </p>
                </div>

                @if (count($attachments) > 0)

                    <span class="text-xs font-medium text-gray-500">
                        {{ count($attachments) }}/5
                    </span>

                @endif

            </div>


            <div
                x-data="{
                    uploading: false,
                    progress: 0
                }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="
                    uploading = false;
                    progress = 0;
                "
                x-on:livewire-upload-error="
                    uploading = false;
                    progress = 0;
                "
                x-on:livewire-upload-cancel="
                    uploading = false;
                    progress = 0;
                "
                x-on:livewire-upload-progress="
                    progress = $event.detail.progress
                "
            >

                <label
                    for="attachments"
                    class="
                        flex cursor-pointer
                        flex-col items-center justify-center
                        rounded-lg
                        border-2 border-dashed border-gray-300
                        bg-gray-50
                        px-6 py-8
                        text-center
                        transition
                        hover:border-indigo-400
                        hover:bg-indigo-50
                    "
                >

                    <svg
                        class="mb-3 h-8 w-8 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 16V4m0 0L8 8m4-4 4 4M5 15v4a1 1 0 001 1h12a1 1 0 001-1v-4"
                        />
                    </svg>

                    <p class="text-sm font-semibold text-gray-700">
                        Seleccionar archivos
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        JPG, PNG, WEBP, PDF, Word, Excel o TXT
                    </p>

                </label>


                <input
                    id="attachments"
                    type="file"
                    wire:model="attachments"
                    multiple
                    accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.txt"
                    class="hidden"
                >


                {{-- PROGRESO --}}
                <div
                    x-show="uploading"
                    x-cloak
                    class="mt-3"
                >

                    <div class="mb-1 flex items-center justify-between">

                        <span class="text-xs font-medium text-gray-600">
                            Cargando archivos...
                        </span>

                        <span
                            class="text-xs font-semibold text-indigo-600"
                            x-text="progress + '%'"
                        ></span>

                    </div>


                    <div class="h-2 overflow-hidden rounded-full bg-gray-200">

                        <div
                            class="h-full rounded-full bg-indigo-600 transition-all"
                            x-bind:style="'width: ' + progress + '%'"
                        ></div>

                    </div>

                </div>

            </div>


            {{-- ERRORES --}}
            <x-input-error
                for="attachments"
                class="mt-2"
            />

            @if ($errors->has('attachments.*'))

                <p class="mt-2 text-sm text-red-600">
                    {{ $errors->first('attachments.*') }}
                </p>

            @endif


            {{-- ========================================================
                ARCHIVOS SELECCIONADOS
            ======================================================== --}}
            @if (count($attachments) > 0)

                <div class="mt-4 grid gap-3 sm:grid-cols-2">

                    @foreach ($attachments as $index => $file)

                        <div
                            wire:key="attachment-{{ $index }}-{{ md5($file->getClientOriginalName()) }}"
                            class="overflow-hidden rounded-lg border border-gray-200 bg-white"
                        >

                            {{-- PREVIEW IMAGEN --}}
                            @if (
                                str_starts_with(
                                    $file->getMimeType() ?? '',
                                    'image/'
                                )
                            )

                                <div class="h-32 bg-gray-100">

                                    <img
                                        src="{{ $file->temporaryUrl() }}"
                                        alt="{{ $file->getClientOriginalName() }}"
                                        class="h-full w-full object-cover"
                                    >

                                </div>

                            @endif


                            <div class="flex items-center justify-between gap-3 p-3">

                                <div class="min-w-0">

                                    <p
                                        class="truncate text-sm font-medium text-gray-800"
                                        title="{{ $file->getClientOriginalName() }}"
                                    >
                                        {{ $file->getClientOriginalName() }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">

                                        @if ($file->getSize() >= 1024 * 1024)

                                            {{ number_format(
                                                $file->getSize() / 1024 / 1024,
                                                2
                                            ) }} MB

                                        @else

                                            {{ number_format(
                                                $file->getSize() / 1024,
                                                1
                                            ) }} KB

                                        @endif

                                    </p>

                                </div>


                                <button
                                    type="button"
                                    wire:click="removeAttachment({{ $index }})"
                                    class="
                                        shrink-0
                                        rounded-md
                                        p-2
                                        text-gray-400
                                        transition
                                        hover:bg-red-50
                                        hover:text-red-600
                                    "
                                    title="Quitar archivo"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>

                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- ============================================================
            BOTONES
        ============================================================ --}}
        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">

            <a
                href="{{ route('tickets.index') }}"
                class="
                    rounded-md
                    border border-gray-300
                    bg-white
                    px-4 py-2
                    text-sm font-semibold
                    text-gray-700
                    shadow-sm
                    transition
                    hover:bg-gray-50
                "
            >
                Cancelar
            </a>


            <button
                type="submit"
                wire:loading.attr="disabled"
                class="
                    rounded-md
                    bg-indigo-600
                    px-5 py-2
                    text-sm font-semibold
                    text-white
                    shadow-sm
                    transition
                    hover:bg-indigo-500
                    disabled:cursor-not-allowed
                    disabled:opacity-50
                "
            >

                <span
                    wire:loading.remove
                    wire:target="save"
                >
                    Crear ticket
                </span>

                <span
                    wire:loading
                    wire:target="save"
                >
                    Creando...
                </span>

            </button>

        </div>

    </form>

</div>