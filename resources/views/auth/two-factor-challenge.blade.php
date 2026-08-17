<x-guest-layout>

    <div
        x-data="{
            recovery: false
        }"
    >

        <div class="mb-8">

            <div
                class="
                    mb-5
                    flex h-12 w-12
                    items-center justify-center
                    rounded-xl
                "
                style="
                    background-color:
                    color-mix(
                        in srgb,
                        {{ $company->primary_color ?? '#4F46E5' }} 12%,
                        white
                    );
                    color:
                    {{ $company->primary_color ?? '#4F46E5' }};
                "
            >

                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                    />
                </svg>

            </div>


            <p
                class="text-sm font-semibold uppercase tracking-wider"
                style="
                    color:
                    {{ $company->primary_color ?? '#4F46E5' }};
                "
            >
                Seguridad
            </p>


            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                Verificación en dos pasos
            </h1>


            <p
                x-show="! recovery"
                class="mt-3 text-sm leading-6 text-gray-500"
            >
                Introduce el código generado por tu aplicación
                de autenticación.
            </p>


            <p
                x-cloak
                x-show="recovery"
                class="mt-3 text-sm leading-6 text-gray-500"
            >
                Introduce uno de los códigos de recuperación
                asociados a tu cuenta.
            </p>

        </div>


        <x-validation-errors class="mb-5" />


        <form
            method="POST"
            action="{{ route('two-factor.login') }}"
            class="space-y-5"
        >
            @csrf


            {{-- =====================================================
                AUTHENTICATOR CODE
            ====================================================== --}}
            <div x-show="! recovery">

                <label
                    for="code"
                    class="block text-sm font-medium text-gray-700"
                >
                    Código de autenticación
                </label>

                <input
                    id="code"
                    type="text"
                    inputmode="numeric"
                    name="code"
                    autofocus
                    x-ref="code"
                    autocomplete="one-time-code"
                    placeholder="000000"

                    class="
                        brand-focus
                        mt-2
                        block w-full
                        rounded-lg
                        border-gray-300
                        px-4 py-3
                        text-center
                        font-mono
                        text-lg
                        tracking-[0.35em]
                        shadow-sm
                    "
                >

            </div>


            {{-- =====================================================
                RECOVERY CODE
            ====================================================== --}}
            <div
                x-cloak
                x-show="recovery"
            >

                <label
                    for="recovery_code"
                    class="block text-sm font-medium text-gray-700"
                >
                    Código de recuperación
                </label>

                <input
                    id="recovery_code"
                    type="text"
                    name="recovery_code"
                    x-ref="recovery_code"
                    autocomplete="one-time-code"

                    class="
                        brand-focus
                        mt-2
                        block w-full
                        rounded-lg
                        border-gray-300
                        px-4 py-3
                        font-mono
                        text-sm
                        shadow-sm
                    "
                >

            </div>


            {{-- CAMBIAR MÉTODO --}}
            <div>

                <button
                    type="button"
                    x-show="! recovery"

                    x-on:click="
                        recovery = true;

                        $nextTick(() => {
                            $refs.recovery_code.focus()
                        });
                    "

                    class="
                        brand-link
                        text-sm
                        font-semibold
                    "
                >
                    Usar un código de recuperación
                </button>


                <button
                    type="button"
                    x-cloak
                    x-show="recovery"

                    x-on:click="
                        recovery = false;

                        $nextTick(() => {
                            $refs.code.focus()
                        });
                    "

                    class="
                        brand-link
                        text-sm
                        font-semibold
                    "
                >
                    Usar código del autenticador
                </button>

            </div>


            {{-- SUBMIT --}}
            <button
                type="submit"

                class="
                    brand-button
                    flex w-full
                    items-center justify-center
                    rounded-lg
                    px-4 py-3
                    text-sm
                    font-semibold
                    shadow-sm
                "
            >
                Verificar y continuar
            </button>

        </form>


        <div
            class="
                mt-7
                rounded-lg
                bg-gray-50
                px-4 py-3
            "
        >

            <p class="text-xs leading-5 text-gray-500">
                Los códigos de recuperación solo pueden utilizarse una vez.
                Guarda los restantes en un lugar seguro.
            </p>

        </div>

    </div>

</x-guest-layout>