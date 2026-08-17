<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ $company->system_name ?? config('app.name', 'Portal de Soporte') }}
    </title>

    @if ($company->faviconUrl())
        <link
            rel="icon"
            href="{{ $company->faviconUrl() }}"
        >
    @endif

    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
        rel="stylesheet"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        :root {
            --brand-primary: {{ $company->primary_color ?? '#4F46E5' }};
            --brand-secondary: {{ $company->secondary_color ?? '#111827' }};
        }

        .brand-button {
            background-color: var(--brand-primary);
            color: white;
        }

        .brand-button:hover {
            filter: brightness(1.08);
        }

        .brand-link {
            color: var(--brand-primary);
        }

        .brand-link:hover {
            filter: brightness(.85);
        }

        .brand-focus:focus {
            border-color: var(--brand-primary) !important;
            --tw-ring-color: var(--brand-primary) !important;
        }
    </style>

    @livewireStyles
</head>


<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100 lg:grid lg:grid-cols-2">

        {{-- ============================================================
            PANEL CORPORATIVO
        ============================================================ --}}
        <section
            class="
                relative
                hidden
                overflow-hidden
                p-10
                text-white
                lg:flex
                lg:flex-col
                lg:justify-between
                xl:p-14
            "
            style="
                background:
                    linear-gradient(
                        145deg,
                        {{ $company->secondary_color ?? '#111827' }} 0%,
                        {{ $company->primary_color ?? '#4F46E5' }} 100%
                    );
            "
        >

            {{-- Decoración --}}
            <div
                class="
                    absolute
                    -right-32
                    -top-32
                    h-96
                    w-96
                    rounded-full
                    bg-white/10
                "
            ></div>

            <div
                class="
                    absolute
                    -bottom-40
                    -left-24
                    h-96
                    w-96
                    rounded-full
                    bg-white/5
                "
            ></div>


            {{-- Marca --}}
            <div class="relative z-10">

                <div class="flex items-center gap-4">

                    <div
                        class="
                            flex
                            h-16
                            w-16
                            shrink-0
                            items-center
                            justify-center
                            overflow-hidden
                            rounded-2xl
                            bg-white
                            p-2
                            shadow-lg
                        "
                    >

                        @if ($company->logoUrl())

                            <img
                                src="{{ $company->logoUrl() }}"
                                alt="{{ $company->company_name }}"
                                class="
                                    max-h-full
                                    max-w-full
                                    object-contain
                                "
                            >

                        @else

                            <span
                                class="text-2xl font-bold"
                                style="
                                    color:
                                    {{ $company->primary_color ?? '#4F46E5' }};
                                "
                            >
                                {{ strtoupper(
                                    substr(
                                        $company->company_name ?? 'E',
                                        0,
                                        1
                                    )
                                ) }}
                            </span>

                        @endif

                    </div>


                    <div class="min-w-0">

                        <p class="text-sm font-medium text-white/70">
                            {{ $company->company_name ?? 'Mi Empresa' }}
                        </p>

                        <h1
                            class="
                                truncate
                                text-2xl
                                font-bold
                            "
                        >
                            {{ $company->system_name ?? 'Portal de Soporte' }}
                        </h1>

                    </div>

                </div>

            </div>


            {{-- Mensaje --}}
            <div class="relative z-10 max-w-xl">

                <p
                    class="
                        text-sm
                        font-semibold
                        uppercase
                        tracking-[0.2em]
                        text-white/60
                    "
                >
                    Centro de soporte
                </p>

                <h2
                    class="
                        mt-4
                        text-4xl
                        font-bold
                        leading-tight
                        xl:text-5xl
                    "
                >
                    Soporte más simple,
                    organizado y eficiente.
                </h2>

                <p
                    class="
                        mt-6
                        max-w-lg
                        text-base
                        leading-7
                        text-white/75
                    "
                >
                    {{ $company->login_message
                        ?: 'Gestiona solicitudes, comunícate con soporte y consulta el progreso de tus tickets desde un solo lugar.'
                    }}
                </p>

            </div>


            {{-- Footer --}}
            <div class="relative z-10 text-sm text-white/50">

                {{ $company->footer_text
                    ?: '© '.date('Y').' '.$company->company_name
                }}

            </div>

        </section>


        {{-- ============================================================
            PANEL DEL FORMULARIO
        ============================================================ --}}
        <main
            class="
                flex
                min-h-screen
                flex-col
                bg-white
            "
        >

            {{-- Branding móvil --}}
            <div
                class="
                    flex
                    items-center
                    gap-3
                    border-b
                    border-gray-100
                    px-5
                    py-4
                    lg:hidden
                "
            >

                <div
                    class="
                        flex
                        h-10
                        w-10
                        shrink-0
                        items-center
                        justify-center
                        overflow-hidden
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        p-1
                    "
                >

                    @if ($company->logoUrl())

                        <img
                            src="{{ $company->logoUrl() }}"
                            alt="{{ $company->company_name }}"
                            class="
                                max-h-full
                                max-w-full
                                object-contain
                            "
                        >

                    @else

                        <span
                            class="font-bold"
                            style="
                                color:
                                {{ $company->primary_color ?? '#4F46E5' }};
                            "
                        >
                            {{ strtoupper(
                                substr(
                                    $company->company_name ?? 'E',
                                    0,
                                    1
                                )
                            ) }}
                        </span>

                    @endif

                </div>


                <div class="min-w-0">

                    <p class="truncate font-bold text-gray-900">
                        {{ $company->system_name }}
                    </p>

                    <p class="truncate text-xs text-gray-500">
                        {{ $company->company_name }}
                    </p>

                </div>

            </div>


            {{-- Slot --}}
            <div
                class="
                    flex
                    flex-1
                    items-center
                    justify-center
                    px-5
                    py-10
                    sm:px-8
                    lg:px-12
                    xl:px-20
                "
            >

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

            </div>


            {{-- Footer móvil --}}
            <div
                class="
                    px-6
                    pb-6
                    text-center
                    text-xs
                    text-gray-400
                    lg:hidden
                "
            >
                {{ $company->footer_text
                    ?: '© '.date('Y').' '.$company->company_name
                }}
            </div>

        </main>

    </div>


    @livewireScripts

</body>

</html>