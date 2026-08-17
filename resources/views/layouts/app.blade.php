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


    {{-- ============================================================
        TÍTULO DINÁMICO
    ============================================================ --}}
    <title>
        {{ $company->system_name ?? config('app.name', 'Portal de Soporte') }}
    </title>


    {{-- ============================================================
        FAVICON
    ============================================================ --}}
    @if ($company->faviconUrl())

        <link
            rel="icon"
            href="{{ $company->faviconUrl() }}"
        >

    @endif


    {{-- Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
        rel="stylesheet"
    >


    {{-- Scripts --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- ============================================================
        BRANDING GLOBAL
    ============================================================ --}}
    <style>

        :root {
            --brand-primary:
                {{ $company->primary_color ?? '#4F46E5' }};

            --brand-secondary:
                {{ $company->secondary_color ?? '#111827' }};
        }


        /*
        |--------------------------------------------------------------------------
        | Utilidades de marca
        |--------------------------------------------------------------------------
        */

        .bg-brand {
            background-color: var(--brand-primary) !important;
        }

        .bg-brand-secondary {
            background-color: var(--brand-secondary) !important;
        }

        .text-brand {
            color: var(--brand-primary) !important;
        }

        .text-brand-secondary {
            color: var(--brand-secondary) !important;
        }

        .border-brand {
            border-color: var(--brand-primary) !important;
        }

        .brand-button {
            background-color: var(--brand-primary);
            color: white;
            transition:
                filter .15s ease,
                transform .15s ease;
        }

        .brand-button:hover {
            filter: brightness(1.08);
        }

        .brand-button:active {
            filter: brightness(.94);
        }

        .brand-link {
            color: var(--brand-primary);
        }

        .brand-link:hover {
            filter: brightness(.85);
        }

        .brand-focus:focus {
            border-color: var(--brand-primary) !important;

            --tw-ring-color:
                color-mix(
                    in srgb,
                    var(--brand-primary) 35%,
                    transparent
                ) !important;
        }

        .brand-ring {
            --tw-ring-color: var(--brand-primary);
        }

    </style>


    @livewireStyles

</head>


<body class="font-sans antialiased">

    <x-banner />


    <div class="min-h-screen bg-gray-100">

        {{-- Navegación --}}
        @livewire('navigation-menu')


        {{-- Header opcional --}}
        @if (isset($header))

            <header
                class="
                    border-b
                    border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div
                    class="
                        mx-auto
                        max-w-7xl
                        px-4 py-5
                        sm:px-6
                        lg:px-8
                    "
                >
                    {{ $header }}
                </div>

            </header>

        @endif


        {{-- Página --}}
        <main>
            {{ $slot }}
        </main>


        {{-- ========================================================
            FOOTER
        ======================================================== --}}
        @if ($company->footer_text)

            <footer
                class="
                    border-t
                    border-gray-200
                    bg-white
                    px-6 py-4
                    text-center
                    text-xs
                    text-gray-400
                "
            >
                {{ $company->footer_text }}
            </footer>

        @endif

    </div>


    @stack('modals')


    @livewireScripts

</body>

</html>