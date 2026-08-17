<x-guest-layout>

    <div>

        <div class="mb-8">

            <p
                class="text-sm font-semibold uppercase tracking-wider"
                style="color: {{ $company->primary_color ?? '#4F46E5' }};"
            >
                {{ $company->company_name }}
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                Recuperar contraseña
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Ingresa el correo asociado a tu cuenta y te enviaremos
                un enlace para establecer una nueva contraseña.
            </p>

        </div>


        @if (session('status'))

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
                {{ session('status') }}
            </div>

        @endif


        <x-validation-errors class="mb-5" />


        <form
            method="POST"
            action="{{ route('password.email') }}"
            class="space-y-5"
        >
            @csrf


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
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nombre@empresa.com"

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
                Enviar enlace de recuperación
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
                ← Volver a iniciar sesión
            </a>

        </div>


        @if ($company->support_email)

            <div
                class="
                    mt-6
                    rounded-lg
                    bg-gray-50
                    px-4 py-3
                    text-center
                "
            >

                <p class="text-xs text-gray-500">
                    ¿Necesitas ayuda?
                </p>

                <a
                    href="mailto:{{ $company->support_email }}"
                    class="brand-link mt-1 inline-block text-sm font-semibold"
                >
                    {{ $company->support_email }}
                </a>

            </div>

        @endif

    </div>

</x-guest-layout>