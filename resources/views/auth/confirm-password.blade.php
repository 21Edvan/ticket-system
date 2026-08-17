<x-guest-layout>

    <div>

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
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4"
                    />
                </svg>

            </div>


            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Confirma tu contraseña
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Estás intentando acceder a una sección protegida.
                Confirma tu contraseña para continuar.
            </p>

        </div>


        <x-validation-errors class="mb-5" />


        <form
            method="POST"
            action="{{ route('password.confirm') }}"
            class="space-y-5"
        >
            @csrf


            <div>

                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                >
                    Contraseña
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autofocus
                    autocomplete="current-password"

                    class="
                        brand-focus
                        mt-2
                        block w-full
                        rounded-lg
                        border-gray-300
                        px-4 py-3
                        text-sm
                        shadow-sm
                    "
                >

            </div>


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
                Confirmar y continuar
            </button>

        </form>

    </div>

</x-guest-layout>