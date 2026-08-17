<x-app-layout>

    <div class="bg-gray-100 py-6">

        <div class="w-full px-4 sm:px-6 lg:px-8">

            {{-- ========================================================
                CABECERA
            ======================================================== --}}
            <div
                class="
                    mb-6
                    overflow-hidden
                    rounded-xl
                    border border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div
                    class="h-24"
                    style="
                        background:
                            linear-gradient(
                                135deg,
                                {{ $company->secondary_color ?? '#111827' }},
                                {{ $company->primary_color ?? '#4F46E5' }}
                            );
                    "
                ></div>


                <div
                    class="
                        flex flex-col
                        gap-4
                        px-5 pb-5
                        sm:flex-row
                        sm:items-end
                        sm:justify-between
                    "
                >

                    <div
                        class="
                            -mt-10
                            flex
                            min-w-0
                            items-end
                            gap-4
                        "
                    >

                        {{-- AVATAR --}}
                        @if (
                            Laravel\Jetstream\Jetstream::managesProfilePhotos()
                        )

                            <img
                                src="{{ auth()->user()->profile_photo_url }}"
                                alt="{{ auth()->user()->name }}"
                                class="
                                    h-20 w-20
                                    shrink-0
                                    rounded-2xl
                                    border-4
                                    border-white
                                    bg-white
                                    object-cover
                                    shadow-md
                                "
                            >

                        @else

                            <div
                                class="
                                    flex
                                    h-20 w-20
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-2xl
                                    border-4
                                    border-white
                                    text-2xl
                                    font-bold
                                    text-white
                                    shadow-md
                                "

                                style="
                                    background-color:
                                    {{ $company->primary_color ?? '#4F46E5' }};
                                "
                            >
                                {{ strtoupper(
                                    substr(
                                        auth()->user()->name,
                                        0,
                                        1
                                    )
                                ) }}
                            </div>

                        @endif


                        <div class="min-w-0 pb-1">

                            <h1
                                class="
                                    truncate
                                    text-xl
                                    font-bold
                                    text-gray-900
                                "
                            >
                                {{ auth()->user()->name }}
                            </h1>

                            <p
                                class="
                                    truncate
                                    text-sm
                                    text-gray-500
                                "
                            >
                                {{ auth()->user()->email }}
                            </p>


                            <div class="mt-2">

                                @if (auth()->user()->isAdmin())

                                    <span
                                        class="
                                            rounded-full
                                            bg-purple-100
                                            px-2.5 py-1
                                            text-xs
                                            font-semibold
                                            text-purple-700
                                        "
                                    >
                                        Administrador
                                    </span>

                                @elseif (auth()->user()->isTechnician())

                                    <span
                                        class="
                                            rounded-full
                                            bg-blue-100
                                            px-2.5 py-1
                                            text-xs
                                            font-semibold
                                            text-blue-700
                                        "
                                    >
                                        Técnico
                                    </span>

                                @else

                                    <span
                                        class="
                                            rounded-full
                                            bg-gray-100
                                            px-2.5 py-1
                                            text-xs
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        Usuario
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="pb-1">

                        <p class="text-sm font-semibold text-gray-900">
                            Mi cuenta
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Seguridad y configuración personal
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                CONTENIDO
            ======================================================== --}}
            <div class="space-y-5">

                {{-- INFORMACIÓN PERSONAL --}}
                @if (
                    Laravel\Fortify\Features::canUpdateProfileInformation()
                )

                    @livewire(
                        'profile.update-profile-information-form'
                    )

                @endif


                {{-- CONTRASEÑA --}}
                @if (
                    Laravel\Fortify\Features::enabled(
                        Laravel\Fortify\Features::updatePasswords()
                    )
                )

                    @livewire(
                        'profile.update-password-form'
                    )

                @endif


                {{-- 2FA --}}
                @if (
                    Laravel\Fortify\Features::canManageTwoFactorAuthentication()
                )

                    @livewire(
                        'profile.two-factor-authentication-form'
                    )

                @endif


                {{-- SESIONES --}}
                @livewire(
                    'profile.logout-other-browser-sessions-form'
                )


                {{-- ELIMINAR CUENTA --}}
                @if (
                    Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures()
                )

                    @livewire(
                        'profile.delete-user-form'
                    )

                @endif

            </div>

        </div>

    </div>

</x-app-layout>