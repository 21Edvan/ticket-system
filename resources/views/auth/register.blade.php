<x-guest-layout>

    <div>

        {{-- ============================================================
            ENCABEZADO
        ============================================================ --}}
        <div class="mb-7">

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
                Crear cuenta
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Regístrate para acceder a
                {{ $company->system_name }}.
            </p>

        </div>


        <x-validation-errors class="mb-5" />


        <form
            method="POST"
            action="{{ route('register') }}"
            class="space-y-4"
        >
            @csrf


            {{-- Nombre --}}
            <div>

                <label
                    for="name"
                    class="
                        block
                        text-sm
                        font-medium
                        text-gray-700
                    "
                >
                    Nombre completo
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"

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

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"

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


            {{-- Confirmación --}}
            <div>

                <label
                    for="password_confirmation"
                    class="
                        block
                        text-sm
                        font-medium
                        text-gray-700
                    "
                >
                    Confirmar contraseña
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"

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


            {{-- Terms --}}
            @if (
                Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature()
            )

                <div class="pt-1">

                    <label
                        for="terms"
                        class="
                            flex
                            items-start
                            gap-2
                        "
                    >

                        <input
                            id="terms"
                            name="terms"
                            type="checkbox"
                            required

                            class="
                                mt-1
                                rounded
                                border-gray-300
                            "

                            style="
                                color:
                                {{ $company->primary_color ?? '#4F46E5' }};
                            "
                        >


                        <span class="text-sm leading-6 text-gray-600">

                            Acepto los

                            <a
                                target="_blank"
                                href="{{ route('terms.show') }}"
                                class="brand-link font-medium"
                            >
                                términos de servicio
                            </a>

                            y la

                            <a
                                target="_blank"
                                href="{{ route('policy.show') }}"
                                class="brand-link font-medium"
                            >
                                política de privacidad
                            </a>.

                        </span>

                    </label>

                </div>

            @endif


            <button
                type="submit"

                class="
                    brand-button
                    mt-2
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
                Crear cuenta
            </button>

        </form>


        <div
            class="
                mt-7
                border-t
                border-gray-100
                pt-6
                text-center
            "
        >

            <p class="text-sm text-gray-500">

                ¿Ya tienes una cuenta?

                <a
                    href="{{ route('login') }}"
                    class="
                        brand-link
                        ms-1
                        font-semibold
                    "
                >
                    Iniciar sesión
                </a>

            </p>

        </div>

    </div>

</x-guest-layout>