<nav x-data="{ open: false }" class="border-b border-gray-100 bg-white">

    {{-- ============================================================
        NAVEGACIÓN PRINCIPAL
    ============================================================ --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex h-16 justify-between">

            {{-- ========================================================
                LADO IZQUIERDO
            ======================================================== --}}
            <div class="flex min-w-0">

                {{-- Logo --}}
                <div class="flex shrink-0 items-center">

                    <a
                        href="{{ route('dashboard') }}"
                        class="
                            flex
                            min-w-0
                            items-center
                            gap-3
                        "
                    >

                        <x-application-mark
                            class="block h-9 w-auto shrink-0"
                        />


                        <div class="hidden min-w-0 lg:block">

                            <p
                                class="
                                    max-w-[180px]
                                    truncate
                                    text-sm
                                    font-bold
                                    text-gray-900
                                "
                            >
                                {{ $company->system_name }}
                            </p>

                            <p
                                class="
                                    max-w-[180px]
                                    truncate
                                    text-[10px]
                                    text-gray-400
                                "
                            >
                                {{ $company->company_name }}
                            </p>

                        </div>

                    </a>

                </div>


                {{-- ====================================================
                    ENLACES DE ESCRITORIO
                ==================================================== --}}
                <div
                    class="
                        hidden
                        space-x-6
                        sm:-my-px
                        sm:ms-8
                        sm:flex
                    "
                >

                    {{-- Dashboard --}}
                    <x-nav-link
                        href="{{ route('dashboard') }}"
                        :active="request()->routeIs('dashboard')"
                    >
                        Dashboard
                    </x-nav-link>


                    {{-- Tickets --}}
                    <x-nav-link
                        href="{{ route('tickets.index') }}"
                        :active="request()->routeIs('tickets.index', 'tickets.show')"
                    >

                        @if (auth()->user()->isAdmin())

                            Todos los tickets

                        @elseif (auth()->user()->isTechnician())

                            Tickets asignados

                        @else

                            Mis tickets

                        @endif

                    </x-nav-link>


                    {{-- Nuevo ticket --}}
                    <x-nav-link
                        href="{{ route('tickets.create') }}"
                        :active="request()->routeIs('tickets.create')"
                    >
                        Nuevo ticket
                    </x-nav-link>


                    {{-- ================================================
                        SOLO ADMINISTRADOR
                    ================================================ --}}
                    @if (auth()->user()->isAdmin())

                        {{-- Categorías --}}
                        <x-nav-link
                            href="{{ route('categories.index') }}"
                            :active="request()->routeIs('categories.*')"
                        >
                            Categorías
                        </x-nav-link>


                        {{-- Usuarios --}}
                        <x-nav-link
                            href="{{ route('admin.users.index') }}"
                            :active="request()->routeIs('admin.users.*')"
                        >
                            Usuarios
                        </x-nav-link>


                        {{-- Personalización --}}
                        <x-nav-link
                            href="{{ route('admin.branding.edit') }}"
                            :active="request()->routeIs('admin.branding.*')"
                        >
                            Personalización
                        </x-nav-link>

                    @endif

                </div>

            </div>


            {{-- ========================================================
                LADO DERECHO - ESCRITORIO
            ======================================================== --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                {{-- Notificaciones --}}
                <div class="ms-3">
                    <livewire:notifications.notification-bell />
                </div>


                {{-- ====================================================
                    TEAMS - JETSTREAM
                ==================================================== --}}
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())

                    <div class="relative ms-3">

                        <x-dropdown align="right" width="60">

                            <x-slot name="trigger">

                                <span class="inline-flex rounded-md">

                                    <button
                                        type="button"
                                        class="
                                            inline-flex
                                            items-center
                                            rounded-md
                                            border
                                            border-transparent
                                            bg-white
                                            px-3 py-2
                                            text-sm
                                            font-medium
                                            leading-4
                                            text-gray-500
                                            transition
                                            hover:text-gray-700
                                            focus:bg-gray-50
                                            focus:outline-none
                                            active:bg-gray-50
                                        "
                                    >
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg
                                            class="ms-2 -me-0.5 size-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                            />
                                        </svg>

                                    </button>

                                </span>

                            </x-slot>


                            <x-slot name="content">

                                <div class="w-60">

                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Administrar equipo
                                    </div>


                                    <x-dropdown-link
                                        href="{{ route(
                                            'teams.show',
                                            Auth::user()->currentTeam->id
                                        ) }}"
                                    >
                                        Configuración del equipo
                                    </x-dropdown-link>


                                    @can(
                                        'create',
                                        Laravel\Jetstream\Jetstream::newTeamModel()
                                    )

                                        <x-dropdown-link
                                            href="{{ route('teams.create') }}"
                                        >
                                            Crear nuevo equipo
                                        </x-dropdown-link>

                                    @endcan


                                    @if (Auth::user()->allTeams()->count() > 1)

                                        <div class="border-t border-gray-200"></div>


                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Cambiar equipo
                                        </div>


                                        @foreach (
                                            Auth::user()->allTeams()
                                            as $team
                                        )

                                            <x-switchable-team :team="$team" />

                                        @endforeach

                                    @endif

                                </div>

                            </x-slot>

                        </x-dropdown>

                    </div>

                @endif


                {{-- ====================================================
                    PERFIL
                ==================================================== --}}
                <div class="relative ms-3">

                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">

                            @if (
                                Laravel\Jetstream\Jetstream::managesProfilePhotos()
                            )

                                <button
                                    class="
                                        flex
                                        rounded-full
                                        border-2
                                        border-transparent
                                        text-sm
                                        transition
                                        focus:border-gray-300
                                        focus:outline-none
                                    "
                                >
                                    <img
                                        class="
                                            size-8
                                            rounded-full
                                            object-cover
                                        "
                                        src="{{ Auth::user()->profile_photo_url }}"
                                        alt="{{ Auth::user()->name }}"
                                    >
                                </button>

                            @else

                                <span class="inline-flex rounded-md">

                                    <button
                                        type="button"
                                        class="
                                            inline-flex
                                            items-center
                                            rounded-md
                                            border
                                            border-transparent
                                            bg-white
                                            px-3 py-2
                                            text-sm
                                            font-medium
                                            leading-4
                                            text-gray-500
                                            transition
                                            hover:text-gray-700
                                            focus:bg-gray-50
                                            focus:outline-none
                                            active:bg-gray-50
                                        "
                                    >

                                        {{ Auth::user()->name }}


                                        <svg
                                            class="ms-2 -me-0.5 size-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                            />
                                        </svg>

                                    </button>

                                </span>

                            @endif

                        </x-slot>


                        <x-slot name="content">

                            {{-- Cuenta --}}
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Mi cuenta
                            </div>


                            <x-dropdown-link
                                href="{{ route('profile.show') }}"
                            >
                                Perfil
                            </x-dropdown-link>


                            @if (
                                Laravel\Jetstream\Jetstream::hasApiFeatures()
                            )

                                <x-dropdown-link
                                    href="{{ route('api-tokens.index') }}"
                                >
                                    Tokens API
                                </x-dropdown-link>

                            @endif


                            <div class="border-t border-gray-200"></div>


                            {{-- Logout --}}
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                x-data
                            >
                                @csrf

                                <x-dropdown-link
                                    href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();"
                                >
                                    Cerrar sesión
                                </x-dropdown-link>

                            </form>

                        </x-slot>

                    </x-dropdown>

                </div>

            </div>


            {{-- ========================================================
                MÓVIL: CAMPANA + HAMBURGUESA
            ======================================================== --}}
            <div class="flex items-center gap-1 sm:hidden">

                {{-- Notificaciones móvil --}}
                <livewire:notifications.notification-bell />


                {{-- Hamburger --}}
                <button
                    type="button"
                    @click="open = ! open"

                    class="
                        inline-flex
                        items-center
                        justify-center
                        rounded-md
                        p-2
                        text-gray-400
                        transition
                        hover:bg-gray-100
                        hover:text-gray-500
                        focus:bg-gray-100
                        focus:text-gray-500
                        focus:outline-none
                    "
                >

                    <span class="sr-only">
                        Abrir menú
                    </span>


                    <svg
                        class="size-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <path
                            :class="{
                                'hidden': open,
                                'inline-flex': ! open
                            }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />


                        <path
                            :class="{
                                'hidden': ! open,
                                'inline-flex': open
                            }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================
        NAVEGACIÓN RESPONSIVE
    ============================================================ --}}
    <div
        :class="{
            'block': open,
            'hidden': ! open
        }"
        class="hidden sm:hidden"
    >

        {{-- ========================================================
            ENLACES PRINCIPALES MÓVIL
        ======================================================== --}}
        <div class="space-y-1 pb-3 pt-2">

            {{-- Dashboard --}}
            <x-responsive-nav-link
                href="{{ route('dashboard') }}"
                :active="request()->routeIs('dashboard')"
            >
                Dashboard
            </x-responsive-nav-link>


            {{-- Tickets --}}
            <x-responsive-nav-link
                href="{{ route('tickets.index') }}"
                :active="request()->routeIs('tickets.index', 'tickets.show')"
            >

                @if (auth()->user()->isAdmin())

                    Todos los tickets

                @elseif (auth()->user()->isTechnician())

                    Tickets asignados

                @else

                    Mis tickets

                @endif

            </x-responsive-nav-link>


            {{-- Nuevo ticket --}}
            <x-responsive-nav-link
                href="{{ route('tickets.create') }}"
                :active="request()->routeIs('tickets.create')"
            >
                Nuevo ticket
            </x-responsive-nav-link>


            {{-- ================================================
                SOLO ADMINISTRADOR
            ================================================ --}}
            @if (auth()->user()->isAdmin())

                {{-- Categorías --}}
                <x-responsive-nav-link
                    href="{{ route('categories.index') }}"
                    :active="request()->routeIs('categories.*')"
                >
                    Categorías
                </x-responsive-nav-link>


                {{-- Usuarios --}}
                <x-responsive-nav-link
                    href="{{ route('admin.users.index') }}"
                    :active="request()->routeIs('admin.users.*')"
                >
                    Usuarios
                </x-responsive-nav-link>


                {{-- Personalización --}}
                <x-responsive-nav-link
                    href="{{ route('admin.branding.edit') }}"
                    :active="request()->routeIs('admin.branding.*')"
                >
                    Personalización
                </x-responsive-nav-link>

            @endif

        </div>


        {{-- ========================================================
            INFORMACIÓN DEL USUARIO
        ======================================================== --}}
        <div class="border-t border-gray-200 pb-1 pt-4">

            <div class="flex items-center px-4">

                @if (
                    Laravel\Jetstream\Jetstream::managesProfilePhotos()
                )

                    <div class="me-3 shrink-0">

                        <img
                            class="
                                size-10
                                rounded-full
                                object-cover
                            "
                            src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}"
                        >

                    </div>

                @endif


                <div class="min-w-0">

                    <div
                        class="
                            truncate
                            text-base
                            font-medium
                            text-gray-800
                        "
                    >
                        {{ Auth::user()->name }}
                    </div>


                    <div
                        class="
                            truncate
                            text-sm
                            font-medium
                            text-gray-500
                        "
                    >
                        {{ Auth::user()->email }}
                    </div>


                    {{-- Rol --}}
                    <div class="mt-1">

                        @if (auth()->user()->isAdmin())

                            <span
                                class="
                                    text-xs
                                    font-medium
                                    text-purple-600
                                "
                            >
                                Administrador
                            </span>

                        @elseif (auth()->user()->isTechnician())

                            <span
                                class="
                                    text-xs
                                    font-medium
                                    text-blue-600
                                "
                            >
                                Técnico
                            </span>

                        @else

                            <span
                                class="
                                    text-xs
                                    font-medium
                                    text-gray-500
                                "
                            >
                                Usuario
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ====================================================
                OPCIONES DE CUENTA MÓVIL
            ==================================================== --}}
            <div class="mt-3 space-y-1">

                <x-responsive-nav-link
                    href="{{ route('profile.show') }}"
                    :active="request()->routeIs('profile.show')"
                >
                    Perfil
                </x-responsive-nav-link>


                @if (
                    Laravel\Jetstream\Jetstream::hasApiFeatures()
                )

                    <x-responsive-nav-link
                        href="{{ route('api-tokens.index') }}"
                        :active="request()->routeIs('api-tokens.index')"
                    >
                        Tokens API
                    </x-responsive-nav-link>

                @endif


                {{-- =================================================
                    TEAMS RESPONSIVE
                ================================================= --}}
                @if (
                    Laravel\Jetstream\Jetstream::hasTeamFeatures()
                )

                    <div class="border-t border-gray-200"></div>


                    <div
                        class="
                            block
                            px-4 py-2
                            text-xs
                            text-gray-400
                        "
                    >
                        Administrar equipo
                    </div>


                    <x-responsive-nav-link
                        href="{{ route(
                            'teams.show',
                            Auth::user()->currentTeam->id
                        ) }}"
                        :active="request()->routeIs('teams.show')"
                    >
                        Configuración del equipo
                    </x-responsive-nav-link>


                    @can(
                        'create',
                        Laravel\Jetstream\Jetstream::newTeamModel()
                    )

                        <x-responsive-nav-link
                            href="{{ route('teams.create') }}"
                            :active="request()->routeIs('teams.create')"
                        >
                            Crear nuevo equipo
                        </x-responsive-nav-link>

                    @endcan


                    @if (
                        Auth::user()->allTeams()->count() > 1
                    )

                        <div class="border-t border-gray-200"></div>


                        <div
                            class="
                                block
                                px-4 py-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Cambiar equipo
                        </div>


                        @foreach (
                            Auth::user()->allTeams()
                            as $team
                        )

                            <x-switchable-team
                                :team="$team"
                                component="responsive-nav-link"
                            />

                        @endforeach

                    @endif

                @endif


                {{-- =================================================
                    CERRAR SESIÓN
                ================================================= --}}
                <div class="border-t border-gray-200"></div>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    x-data
                >
                    @csrf

                    <x-responsive-nav-link
                        href="{{ route('logout') }}"
                        @click.prevent="$root.submit();"
                    >
                        Cerrar sesión
                    </x-responsive-nav-link>

                </form>

            </div>

        </div>

    </div>

</nav>