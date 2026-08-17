<div>

    {{-- ============================================================
        CABECERA
    ============================================================ --}}
    <div
        class="
            mb-5
            flex flex-col gap-3
            md:flex-row
            md:items-center
            md:justify-between
        "
    >

        <div>

            <h1 class="text-xl font-bold text-gray-900">
                Personalización
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Configura la identidad visual de esta instalación.
            </p>

        </div>

    </div>


    {{-- ============================================================
        MENSAJES
    ============================================================ --}}
    @if (session('success'))

        <div
            class="
                mb-5
                rounded-lg
                border border-green-200
                bg-green-50
                px-4 py-3
                text-sm
                text-green-700
            "
        >
            {{ session('success') }}
        </div>

    @endif


    @error('form')

        <div
            class="
                mb-5
                rounded-lg
                border border-red-200
                bg-red-50
                px-4 py-3
                text-sm
                text-red-700
            "
        >
            {{ $message }}
        </div>

    @enderror


    <form
        wire:submit="save"
        class="grid gap-5 xl:grid-cols-12"
    >

        {{-- ========================================================
            FORMULARIO PRINCIPAL
        ======================================================== --}}
        <div class="space-y-5 xl:col-span-8">


            {{-- ====================================================
                EMPRESA
            ==================================================== --}}
            <div
                class="
                    overflow-hidden
                    rounded-xl
                    border border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div class="border-b border-gray-200 px-5 py-4">

                    <h2 class="font-semibold text-gray-900">
                        Información de la empresa
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Datos principales que identifican la instalación.
                    </p>

                </div>


                <div class="grid gap-5 p-5 md:grid-cols-2">

                    {{-- EMPRESA --}}
                    <div>

                        <label
                            for="company_name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Nombre de la empresa
                        </label>

                        <input
                            id="company_name"
                            type="text"
                            wire:model="company_name"
                            maxlength="150"

                            class="
                                mt-1
                                block w-full
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                        <x-input-error
                            for="company_name"
                            class="mt-2"
                        />

                    </div>


                    {{-- SISTEMA --}}
                    <div>

                        <label
                            for="system_name"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Nombre del sistema
                        </label>

                        <input
                            id="system_name"
                            type="text"
                            wire:model="system_name"
                            maxlength="150"

                            class="
                                mt-1
                                block w-full
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                        <x-input-error
                            for="system_name"
                            class="mt-2"
                        />

                    </div>


                    {{-- CORREO --}}
                    <div class="md:col-span-2">

                        <label
                            for="support_email"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Correo de soporte
                        </label>

                        <input
                            id="support_email"
                            type="email"
                            wire:model="support_email"

                            placeholder="soporte@empresa.com"

                            class="
                                mt-1
                                block w-full
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                        <x-input-error
                            for="support_email"
                            class="mt-2"
                        />

                    </div>

                </div>

            </div>


            {{-- ====================================================
                IDENTIDAD VISUAL
            ==================================================== --}}
            <div
                class="
                    overflow-hidden
                    rounded-xl
                    border border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div class="border-b border-gray-200 px-5 py-4">

                    <h2 class="font-semibold text-gray-900">
                        Identidad visual
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Logo, favicon y colores corporativos.
                    </p>

                </div>


                <div class="space-y-6 p-5">

                    {{-- ================================================
                        LOGO
                    ================================================ --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Logo
                        </label>

                        <p class="mt-1 text-xs text-gray-500">
                            JPG, PNG o WEBP. Máximo 2 MB.
                        </p>


                        <div
                            class="
                                mt-3
                                flex flex-col gap-4
                                sm:flex-row
                                sm:items-center
                            "
                        >

                            {{-- PREVIEW --}}
                            <div
                                class="
                                    flex
                                    h-24 w-48
                                    shrink-0
                                    items-center
                                    justify-center
                                    overflow-hidden
                                    rounded-lg
                                    border
                                    border-gray-200
                                    bg-gray-50
                                    p-3
                                "
                            >

                                @if ($logo)

                                    <img
                                        src="{{ $logo->temporaryUrl() }}"
                                        alt="Nuevo logo"
                                        class="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                        "
                                    >

                                @elseif ($currentLogoUrl)

                                    <img
                                        src="{{ $currentLogoUrl }}"
                                        alt="Logo actual"
                                        class="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                        "
                                    >

                                @else

                                    <div class="text-center">

                                        <div
                                            class="
                                                mx-auto
                                                flex h-10 w-10
                                                items-center
                                                justify-center
                                                rounded-lg
                                                bg-gray-200
                                                text-lg
                                                font-bold
                                                text-gray-500
                                            "
                                        >
                                            {{ strtoupper(
                                                substr(
                                                    $company_name ?: 'E',
                                                    0,
                                                    1
                                                )
                                            ) }}
                                        </div>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Sin logo
                                        </p>

                                    </div>

                                @endif

                            </div>


                            <div class="flex-1">

                                <input
                                    id="logo"
                                    type="file"
                                    wire:model="logo"
                                    accept=".jpg,.jpeg,.png,.webp"

                                    class="
                                        block w-full
                                        text-sm
                                        text-gray-500

                                        file:mr-4
                                        file:rounded-md
                                        file:border-0
                                        file:bg-indigo-50
                                        file:px-4
                                        file:py-2
                                        file:text-sm
                                        file:font-semibold
                                        file:text-indigo-700

                                        hover:file:bg-indigo-100
                                    "
                                >

                                <x-input-error
                                    for="logo"
                                    class="mt-2"
                                />


                                @if ($settings->logo_path)

                                    <button
                                        type="button"
                                        wire:click="removeLogo"
                                        wire:confirm="¿Seguro que deseas eliminar el logo actual?"

                                        class="
                                            mt-3
                                            text-xs
                                            font-semibold
                                            text-red-600
                                            hover:underline
                                        "
                                    >
                                        Eliminar logo actual
                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="border-t border-gray-100"></div>


                    {{-- ================================================
                        FAVICON
                    ================================================ --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700">
                            Favicon
                        </label>

                        <p class="mt-1 text-xs text-gray-500">
                            PNG o ICO. Máximo 1 MB.
                        </p>


                        <div
                            class="
                                mt-3
                                flex flex-col gap-4
                                sm:flex-row
                                sm:items-center
                            "
                        >

                            <div
                                class="
                                    flex
                                    h-16 w-16
                                    shrink-0
                                    items-center
                                    justify-center
                                    overflow-hidden
                                    rounded-lg
                                    border
                                    border-gray-200
                                    bg-gray-50
                                    p-2
                                "
                            >

                                @if (
                                    $favicon
                                    && str_starts_with(
                                        $favicon->getMimeType() ?? '',
                                        'image/'
                                    )
                                )

                                    <img
                                        src="{{ $favicon->temporaryUrl() }}"
                                        alt="Nuevo favicon"
                                        class="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                        "
                                    >

                                @elseif ($currentFaviconUrl)

                                    <img
                                        src="{{ $currentFaviconUrl }}"
                                        alt="Favicon actual"
                                        class="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                        "
                                    >

                                @else

                                    <span
                                        class="
                                            text-xl
                                            font-bold
                                            text-gray-400
                                        "
                                    >
                                        {{ strtoupper(
                                            substr(
                                                $company_name ?: 'E',
                                                0,
                                                1
                                            )
                                        ) }}
                                    </span>

                                @endif

                            </div>


                            <div class="flex-1">

                                <input
                                    id="favicon"
                                    type="file"
                                    wire:model="favicon"
                                    accept=".png,.ico"

                                    class="
                                        block w-full
                                        text-sm
                                        text-gray-500

                                        file:mr-4
                                        file:rounded-md
                                        file:border-0
                                        file:bg-indigo-50
                                        file:px-4
                                        file:py-2
                                        file:text-sm
                                        file:font-semibold
                                        file:text-indigo-700

                                        hover:file:bg-indigo-100
                                    "
                                >

                                <x-input-error
                                    for="favicon"
                                    class="mt-2"
                                />


                                @if ($settings->favicon_path)

                                    <button
                                        type="button"
                                        wire:click="removeFavicon"
                                        wire:confirm="¿Seguro que deseas eliminar el favicon actual?"

                                        class="
                                            mt-3
                                            text-xs
                                            font-semibold
                                            text-red-600
                                            hover:underline
                                        "
                                    >
                                        Eliminar favicon actual
                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="border-t border-gray-100"></div>


                    {{-- ================================================
                        COLORES
                    ================================================ --}}
                    <div class="grid gap-5 md:grid-cols-2">

                        {{-- PRINCIPAL --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Color principal
                            </label>


                            <div class="mt-2 flex gap-2">

                                <input
                                    type="color"
                                    wire:model.live="primary_color"

                                    class="
                                        h-10 w-14
                                        cursor-pointer
                                        rounded-md
                                        border
                                        border-gray-300
                                        bg-white
                                        p-1
                                    "
                                >


                                <input
                                    type="text"
                                    wire:model.live="primary_color"
                                    maxlength="7"

                                    class="
                                        block
                                        min-w-0
                                        flex-1
                                        rounded-md
                                        border-gray-300
                                        font-mono
                                        text-sm
                                        uppercase
                                        shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                    "
                                >

                            </div>

                            <x-input-error
                                for="primary_color"
                                class="mt-2"
                            />

                        </div>


                        {{-- SECUNDARIO --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Color secundario
                            </label>


                            <div class="mt-2 flex gap-2">

                                <input
                                    type="color"
                                    wire:model.live="secondary_color"

                                    class="
                                        h-10 w-14
                                        cursor-pointer
                                        rounded-md
                                        border
                                        border-gray-300
                                        bg-white
                                        p-1
                                    "
                                >


                                <input
                                    type="text"
                                    wire:model.live="secondary_color"
                                    maxlength="7"

                                    class="
                                        block
                                        min-w-0
                                        flex-1
                                        rounded-md
                                        border-gray-300
                                        font-mono
                                        text-sm
                                        uppercase
                                        shadow-sm
                                        focus:border-indigo-500
                                        focus:ring-indigo-500
                                    "
                                >

                            </div>

                            <x-input-error
                                for="secondary_color"
                                class="mt-2"
                            />

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                LOGIN
            ==================================================== --}}
            <div
                class="
                    overflow-hidden
                    rounded-xl
                    border border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div class="border-b border-gray-200 px-5 py-4">

                    <h2 class="font-semibold text-gray-900">
                        Pantalla de acceso
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Contenido que aparecerá en el nuevo login.
                    </p>

                </div>


                <div class="space-y-5 p-5">

                    <div>

                        <label
                            for="login_title"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Título de bienvenida
                        </label>

                        <input
                            id="login_title"
                            type="text"
                            wire:model="login_title"
                            maxlength="150"

                            class="
                                mt-1
                                block w-full
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                        <x-input-error
                            for="login_title"
                            class="mt-2"
                        />

                    </div>


                    <div>

                        <label
                            for="login_message"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Mensaje de bienvenida
                        </label>

                        <textarea
                            id="login_message"
                            wire:model="login_message"
                            rows="4"
                            maxlength="1000"

                            class="
                                mt-1
                                block w-full
                                resize-y
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        ></textarea>

                        <x-input-error
                            for="login_message"
                            class="mt-2"
                        />

                    </div>


                    <div>

                        <label
                            for="footer_text"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Texto del pie de página
                        </label>

                        <input
                            id="footer_text"
                            type="text"
                            wire:model="footer_text"
                            maxlength="255"

                            placeholder="© 2026 Mi Empresa"

                            class="
                                mt-1
                                block w-full
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                        <x-input-error
                            for="footer_text"
                            class="mt-2"
                        />

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            PREVIEW
        ======================================================== --}}
        <div class="xl:col-span-4">

            <div class="sticky top-5">

                <div
                    class="
                        overflow-hidden
                        rounded-xl
                        border border-gray-200
                        bg-white
                        shadow-sm
                    "
                >

                    <div class="border-b border-gray-200 px-5 py-4">

                        <h2 class="font-semibold text-gray-900">
                            Vista previa
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Aproximación de la identidad seleccionada.
                        </p>

                    </div>


                    {{-- BARRA --}}
                    <div
                        class="p-5 text-white"
                        style="
                            background-color:
                            {{ $secondary_color ?: '#111827' }};
                        "
                    >

                        <div class="flex items-center gap-3">

                            <div
                                class="
                                    flex h-11 w-11
                                    shrink-0
                                    items-center
                                    justify-center
                                    overflow-hidden
                                    rounded-lg
                                    bg-white
                                    p-1.5
                                "
                            >

                                @if ($logo)

                                    <img
                                        src="{{ $logo->temporaryUrl() }}"
                                        class="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                        "
                                    >

                                @elseif ($currentLogoUrl)

                                    <img
                                        src="{{ $currentLogoUrl }}"
                                        class="
                                            max-h-full
                                            max-w-full
                                            object-contain
                                        "
                                    >

                                @else

                                    <span
                                        class="
                                            text-lg
                                            font-bold
                                            text-gray-700
                                        "
                                    >
                                        {{ strtoupper(
                                            substr(
                                                $company_name ?: 'E',
                                                0,
                                                1
                                            )
                                        ) }}
                                    </span>

                                @endif

                            </div>


                            <div class="min-w-0">

                                <p class="truncate font-bold">
                                    {{ $system_name ?: 'Portal de Soporte' }}
                                </p>

                                <p class="truncate text-xs text-white/70">
                                    {{ $company_name ?: 'Mi Empresa' }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- LOGIN PREVIEW --}}
                    <div class="bg-gray-50 p-6">

                        <div
                            class="
                                rounded-xl
                                border
                                border-gray-200
                                bg-white
                                p-5
                                shadow-sm
                            "
                        >

                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $login_title ?: 'Bienvenido' }}
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                {{
                                    $login_message
                                    ?: 'Accede al portal para gestionar tus solicitudes de soporte.'
                                }}
                            </p>


                            <div class="mt-5 space-y-3">

                                <div>

                                    <div class="mb-1 h-2 w-14 rounded bg-gray-200"></div>

                                    <div
                                        class="
                                            h-9
                                            rounded-md
                                            border
                                            border-gray-200
                                            bg-gray-50
                                        "
                                    ></div>

                                </div>


                                <div>

                                    <div class="mb-1 h-2 w-20 rounded bg-gray-200"></div>

                                    <div
                                        class="
                                            h-9
                                            rounded-md
                                            border
                                            border-gray-200
                                            bg-gray-50
                                        "
                                    ></div>

                                </div>


                                <div
                                    class="
                                        flex h-10
                                        items-center
                                        justify-center
                                        rounded-md
                                        text-sm
                                        font-semibold
                                        text-white
                                    "
                                    style="
                                        background-color:
                                        {{ $primary_color ?: '#4F46E5' }};
                                    "
                                >
                                    Iniciar sesión
                                </div>

                            </div>

                        </div>

                    </div>


                    @if ($footer_text)

                        <div
                            class="
                                border-t
                                border-gray-100
                                px-5 py-3
                                text-center
                                text-xs
                                text-gray-400
                            "
                        >
                            {{ $footer_text }}
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ========================================================
            GUARDAR
        ======================================================== --}}
        <div
            class="
                flex items-center
                justify-end gap-3
                xl:col-span-12
            "
        >

            <button
                type="submit"

                wire:loading.attr="disabled"

                wire:target="save,logo,favicon"

                class="
                    rounded-md
                    bg-indigo-600
                    px-5 py-2.5
                    text-sm
                    font-semibold
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
                    Guardar personalización
                </span>

                <span
                    wire:loading
                    wire:target="save"
                >
                    Guardando...
                </span>

            </button>

        </div>

    </form>

</div>