<x-guest-layout>

    <div>

        <div class="mb-7">

            <p
                class="text-sm font-semibold uppercase tracking-wider"
                style="color: {{ $company->primary_color ?? '#4F46E5' }};"
            >
                {{ $company->company_name }}
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                Nueva contraseña
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Define una nueva contraseña para acceder a
                {{ $company->system_name }}.
            </p>

        </div>


        <x-validation-errors class="mb-5" />


        <form
            method="POST"
            action="{{ route('password.update') }}"
            class="space-y-4"
        >
            @csrf


            <input
                type="hidden"
                name="token"
                value="{{ $request->route('token') }}"
            >


            {{-- EMAIL --}}
            <div>

                <label
                    for="email"
                    class="block text-sm font-medium text-gray-700"
                >
                    Correo electrónico
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"

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


            {{-- PASSWORD --}}
            <div>

                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                >
                    Nueva contraseña
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
                        block w-full
                        rounded-lg
                        border-gray-300
                        px-4 py-3
                        text-sm
                        shadow-sm
                    "
                >

            </div>


            {{-- CONFIRM --}}
            <div>

                <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-gray-700"
                >
                    Confirmar nueva contraseña
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
                    mt-2
                    flex w-full
                    items-center justify-center
                    rounded-lg
                    px-4 py-3
                    text-sm
                    font-semibold
                    shadow-sm
                "
            >
                Cambiar contraseña
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

            <a
                href="{{ route('login') }}"
                class="brand-link text-sm font-semibold"
            >
                Volver a iniciar sesión
            </a>

        </div>

    </div>

</x-guest-layout>