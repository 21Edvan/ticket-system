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
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8V6a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"
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
                {{ $company->system_name }}
            </p>


            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                Verifica tu correo
            </h1>


            <p class="mt-3 text-sm leading-6 text-gray-500">
                Antes de continuar, abre el mensaje que enviamos a tu
                correo electrónico y pulsa el enlace de verificación.
            </p>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Si no recibiste el mensaje, puedes solicitar uno nuevo.
            </p>

        </div>


        @if (session('status') === 'verification-link-sent')

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
                Se envió un nuevo enlace de verificación
                a tu correo electrónico.
            </div>

        @endif


        <form
            method="POST"
            action="{{ route('verification.send') }}"
        >
            @csrf

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
                Reenviar correo de verificación
            </button>

        </form>


        <div
            class="
                mt-7
                flex flex-col
                gap-3
                border-t
                border-gray-100
                pt-6
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >

            <a
                href="{{ route('profile.show') }}"
                class="brand-link text-sm font-semibold"
            >
                Editar perfil
            </a>


            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="
                        text-sm
                        font-semibold
                        text-gray-500
                        hover:text-gray-800
                    "
                >
                    Cerrar sesión
                </button>

            </form>

        </div>

    </div>

</x-guest-layout>