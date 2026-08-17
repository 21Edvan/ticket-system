<x-guest-layout>

    <div>

        {{-- ============================================================
            ENCABEZADO
        ============================================================ --}}
        <div class="mb-8">

            <p
                class="
                    text-sm
                    font-semibold
                    uppercase
                    tracking-wider
                "
                style="
                    color:
                    {{ $company->primary_color ?? '#4F46E5' }};
                "
            >
                {{ $company->company_name }}
            </p>

            <h1
                class="
                    mt-2
                    text-3xl
                    font-bold
                    tracking-tight
                    text-gray-900
                "
            >
                {{ $company->login_title ?: 'Bienvenido' }}
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Ingresa tus credenciales para acceder a
                {{ $company->system_name }}.
            </p>

        </div>


        {{-- ============================================================
            ESTADO
        ============================================================ --}}
        @session('status')

            <div
                class="
                    mb-5
                    rounded-lg
                    border
                    border-green-200
                    bg-green-50
                    px-4
                    py-3
                    text-sm
                    text-green-700
                "
            >
                {{ $value }}
            </div>

        @endsession


        {{-- ============================================================
            ERRORES
        ============================================================ --}}
        <x-validation-errors class="mb-5" />


        {{-- ============================================================
            FORMULARIO
        ============================================================ --}}
        <form
            method="POST"
            action="{{ route('login') }}"
            class="space-y-5"
        >
            @csrf


            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="
                        block
                        text-sm
                        font-medium
                        text-gray-700
                    "
                >
                    Correo electrónico
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"

                    placeholder="nombre@empresa.com"

                    class="
                        brand-focus
                        mt-2
                        block
                        w-full
                        rounded-lg
                        border-gray-300
                        px-4
                        py-3
                        text-sm
                        shadow-sm
                    "
                >

            </div>


            {{-- Password --}}
            <div>

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-4
                    "
                >

                    <label
                        for="password"
                        class="
                            block
                            text-sm
                            font-medium
                            text-gray-700
                        "
                    >
                        Contraseña
                    </label>


                    @if (Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="
                                brand-link
                                text-xs
                                font-semibold
                            "
                        >
                            ¿Olvidaste tu contraseña?
                        </a>

                    @endif

                </div>


                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"

                    placeholder="••••••••"

                    class="
                        brand-focus
                        mt-2
                        block
                        w-full
                        rounded-lg
                        border-gray-300
                        px-4
                        py-3
                        text-sm
                        shadow-sm
                    "
                >

            </div>


            {{-- Remember --}}
            <div class="flex items-center">

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"

                    class="
                        rounded
                        border-gray-300
                        shadow-sm
                    "

                    style="
                        color:
                        {{ $company->primary_color ?? '#4F46E5' }};
                    "
                >

                <label
                    for="remember_me"
                    class="
                        ms-2
                        text-sm
                        text-gray-600
                    "
                >
                    Mantener sesión iniciada
                </label>

            </div>


            {{-- Submit --}}
            <button
                type="submit"

                class="
                    brand-button
                    flex
                    w-full
                    items-center
                    justify-center
                    rounded-lg
                    px-4
                    py-3
                    text-sm
                    font-semibold
                    shadow-sm
                    transition
                "
            >
                Iniciar sesión
            </button>

        </form>


        {{-- ============================================================
            REGISTRO
        ============================================================ --}}
        @if (Route::has('register'))

            <div
                class="
                    mt-8
                    border-t
                    border-gray-100
                    pt-6
                    text-center
                "
            >

                <p class="text-sm text-gray-500">

                    ¿No tienes una cuenta?

                    <a
                        href="{{ route('register') }}"
                        class="
                            brand-link
                            ms-1
                            font-semibold
                        "
                    >
                        Crear cuenta
                    </a>

                </p>

            </div>

        @endif


        @if ($company->support_email)

            <div
                class="
                    mt-8
                    rounded-lg
                    bg-gray-50
                    px-4
                    py-3
                    text-center
                "
            >

                <p class="text-xs text-gray-500">
                    ¿Necesitas ayuda?
                </p>

                <a
                    href="mailto:{{ $company->support_email }}"
                    class="
                        brand-link
                        mt-1
                        inline-block
                        text-sm
                        font-semibold
                    "
                >
                    {{ $company->support_email }}
                </a>

            </div>

        @endif

    </div>

</x-guest-layout>