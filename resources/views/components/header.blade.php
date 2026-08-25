@php

    $showUser = $showUser ?? false;

    $nombreCompleto = session(
        'moodle_fullname',
        session('moodle_username', 'Usuario')
    );

    // Quitar títulos académicos al inicio del nombre.
    $nombreSinTitulo = preg_replace(
        '/^(M\.?C\.?|Dr\.?|Dra\.?|Ing\.?|Lic\.?|Mtro\.?|Mtra\.?)\s+/iu',
        '',
        trim($nombreCompleto)
    );

    // Obtener únicamente el primer nombre.
    $partesNombre = preg_split(
        '/\s+/',
        trim($nombreSinTitulo)
    );

    $usuario = $partesNombre[0] ?? 'Usuario';

@endphp

<header class="site-header">

    <div class="site-header__container">

        {{-- LOGOS --}}
        <div class="site-header__branding">

            <img
                src="{{ asset('images/logos/utm_logo3.png') }}"
                alt="Universidad Tecnológica de la Mixteca"
                class="site-header__logo site-header__logo--utm"
            >

            <div class="site-header__divider"></div>

            <img
                src="{{ asset('images/logos/usalab_logo2.png') }}"
                alt="UsaLab"
                class="site-header__logo site-header__logo--usalab"
            >

        </div>


        {{-- TÍTULO --}}
        <h1 class="site-header__title">
            Sistema de Gestión y Análisis de Evidencias
        </h1>


        {{-- USUARIO --}}
        @if($showUser)

            <div class="site-header__profile">

                {{-- INFORMACIÓN DEL USUARIO --}}
                <div class="profile-user">

                    <span class="profile-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <circle
                                cx="12"
                                cy="8"
                                r="4"
                            ></circle>

                            <path
                                d="M4 21a8 8 0 0 1 16 0"
                            ></path>

                        </svg>

                    </span>


                    <span class="profile-name">

                        {{ $usuario }}

                    </span>


                    {{-- FLECHA --}}
                    <button
                        type="button"
                        class="profile-arrow"
                        id="profileMenuButton"
                        aria-label="Abrir menú de usuario"
                        aria-expanded="false"
                        aria-controls="profileDropdown"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >

                            <path
                                d="m6 9 6 6 6-6"
                            ></path>

                        </svg>

                    </button>

                </div>


                {{-- =================================================
                     MENÚ DESPLEGABLE
                ================================================== --}}

                <div
                    class="profile-dropdown"
                    id="profileDropdown"
                    aria-hidden="true"
                >

                    {{-- PREGUNTAS FRECUENTES --}}
                    <a
                        href="#"
                        class="profile-dropdown__item"
                    >

                        <span class="profile-dropdown__icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                ></circle>

                                <path
                                    d="M9.8 9a2.4 2.4 0 1 1 3.9 1.9c-1 .7-1.7 1.2-1.7 2.6"
                                ></path>

                                <path
                                    d="M12 17h.01"
                                ></path>

                            </svg>

                        </span>

                        <span>
                            Preguntas frecuentes
                        </span>

                    </a>


                    {{-- CONTACTO Y SOPORTE --}}
                    <a
                        href="#"
                        class="profile-dropdown__item"
                    >

                        <span class="profile-dropdown__icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >

                                <path
                                    d="M4 14v-2a8 8 0 0 1 16 0v2"
                                ></path>

                                <path
                                    d="M18 19h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2h-1v7Z"
                                ></path>

                                <path
                                    d="M6 19H5a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1v7Z"
                                ></path>

                            </svg>

                        </span>

                        <span>
                            Contacto y soporte
                        </span>

                    </a>


                    {{-- ACERCA DE --}}
                    <a
                        href="#"
                        class="profile-dropdown__item"
                    >

                        <span class="profile-dropdown__icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                ></circle>

                                <path
                                    d="M12 11v5"
                                ></path>

                                <path
                                    d="M12 8h.01"
                                ></path>

                            </svg>

                        </span>

                        <span>
                            Acerca de
                        </span>

                    </a>


                    {{-- SEPARADOR --}}
                    <div class="profile-dropdown__separator"></div>


                    {{-- CERRAR SESIÓN --}}
                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                        class="profile-dropdown__logout-form"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="profile-dropdown__item profile-dropdown__item--logout"
                        >

                            <span class="profile-dropdown__icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M10 17l5-5-5-5"
                                    ></path>

                                    <path
                                        d="M15 12H3"
                                    ></path>

                                    <path
                                        d="M21 19V5a2 2 0 0 0-2-2h-6"
                                    ></path>

                                </svg>

                            </span>

                            <span>
                                Cerrar sesión
                            </span>

                        </button>

                    </form>

                </div>

            </div>

        @else

            <div class="site-header__spacer"></div>

        @endif

    </div>

</header>