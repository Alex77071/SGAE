@php

    $showUser = $showUser ?? false;

    $nombreCompleto = session(
        'moodle_fullname',
        session('moodle_username', 'Usuario')
    );

    $nombreSinTitulo = preg_replace(
        '/^(M\.?C\.?|Dr\.?|Dra\.?|Ing\.?|Lic\.?|Mtro\.?|Mtra\.?)\s+/iu',
        '',
        trim($nombreCompleto)
    );

    $partesNombre = preg_split(
        '/\s+/',
        trim($nombreSinTitulo)
    );

    $usuario = $partesNombre[0] ?? 'Usuario';

    $fotoUsuario = session('moodle_userpictureurl');

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
    href="{{ route('preguntas.frecuentes') }}"
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
                    <button
                        type="button"
                        id="openAboutModal"
                        class="profile-dropdown__item"
                    >
                        <span class="profile-dropdown__icon" aria-hidden="true">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 11v5"></path>
                                <path d="M12 8h.01"></path>
                            </svg>

                        </span>

                        <span>
                            Acerca de
                        </span>
                    </button>


                    {{-- SEPARADOR --}}
                    <div class="profile-dropdown__separator"></div>


                    {{-- CERRAR SESIÓN --}}
                        <button
                            type="button"
                            id="openLogoutModal"
                            class="profile-dropdown__item profile-dropdown__item--logout"
                        >
                            <span class="profile-dropdown__icon" aria-hidden="true">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M10 17l5-5-5-5"></path>
                                    <path d="M15 12H3"></path>
                                    <path d="M21 19V5a2 2 0 0 0-2-2h-6"></path>
                                </svg>

                            </span>

                            <span>
                                Cerrar sesión
                            </span>
                        </button>

                </div>

            </div>

        @else

            <div class="site-header__spacer"></div>

        @endif

    </div>

    {{-- =====================================================
     MODAL CERRAR SESIÓN
===================================================== --}}

<div
    class="logout-modal"
    id="logoutModal"
    aria-hidden="true"
>

    <div
        class="logout-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="logoutModalTitle"
    >

        <div class="logout-modal__icon" aria-hidden="true">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M10 17l5-5-5-5"></path>
                <path d="M15 12H3"></path>
                <path d="M21 19V5a2 2 0 0 0-2-2h-6"></path>
            </svg>

        </div>

        <h2
            class="logout-modal__title"
            id="logoutModalTitle"
        >
            ¿Deseas cerrar sesión?
        </h2>

        <div class="logout-modal__actions">

            <button
                type="button"
                id="cancelLogout"
                class="logout-modal__button logout-modal__button--cancel"
            >
                Cancelar
            </button>

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-modal__form"
            >
                @csrf

                <button
                    type="submit"
                    class="logout-modal__button logout-modal__button--confirm"
                >
                    Cerrar sesión
                </button>

            </form>

        </div>

    </div>

</div>
{{-- =====================================================
     MODAL ACERCA DE
===================================================== --}}

<div
    class="about-modal"
    id="aboutModal"
    aria-hidden="true"
>

    <section
        class="about-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="aboutModalTitle"
    >

        {{-- ENCABEZADO --}}
            <div class="about-modal__header">

                <h2
                    class="about-modal__header-title"
                    id="aboutModalTitle"
                >
                    Acerca de Sistema de Gestión y Análisis de Evidencias
                </h2>

                <button
                    type="button"
                    id="closeAboutModal"
                    class="about-modal__close"
                    aria-label="Cerrar ventana"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M18 6L6 18"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </button>

            </div>


        {{-- CONTENIDO --}}
        <div class="about-modal__content">

            <div class="about-modal__intro">

                <div class="about-modal__brand">

                    <img
                        src="{{ asset('images/logos/usalab_logo2.png') }}"
                        alt="UsaLab - Laboratorio de Usabilidad"
                        class="about-modal__main-logo"
                    >

                </div>


                <p class="about-modal__description">

                    El Sistema de Gestión y Análisis de Evidencias
                    permite descargar, analizar y consultar las
                    evidencias de los exámenes realizados en Moodle
                    mediante técnicas de Inteligencia Artificial.

                </p>

            </div>


            {{-- INFORMACIÓN INFERIOR --}}
            <div class="about-modal__cards">


                {{-- VERSIÓN --}}
                <article class="about-info-card">

                    <span class="about-info-card__icon" aria-hidden="true">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="12" r="8"></circle>
                            <path d="M12 8v4l3 2"></path>
                        </svg>

                    </span>

                    <h3>
                        Versión
                    </h3>

                    <p>
                        1.0
                    </p>

                </article>


                {{-- UNIVERSIDAD --}}
                <article class="about-info-card">

                    <span class="about-info-card__icon" aria-hidden="true">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m3 9 9-5 9 5"></path>
                            <path d="M5 10h14"></path>
                            <path d="M6 10v8"></path>
                            <path d="M10 10v8"></path>
                            <path d="M14 10v8"></path>
                            <path d="M18 10v8"></path>
                            <path d="M4 18h16"></path>
                            <path d="M3 21h18"></path>
                        </svg>

                    </span>

                    <h3>
                        Universidad
                    </h3>

                    <p>
                        Universidad Tecnológica<br>
                        de la Mixteca
                    </p>

                </article>


                {{-- DESARROLLADO POR --}}
                <article class="about-info-card">

                    <span class="about-info-card__icon" aria-hidden="true">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M5 21a7 7 0 0 1 14 0"></path>
                            <path d="m17 13 2 2 4-4"></path>
                        </svg>

                    </span>

                    <h3>
                        Desarrollado por
                    </h3>

                    <p>
                        UsaLab<br>
                        Laboratorio de Usabilidad
                    </p>

                </article>

            </div>

        </div>

    </section>

</div>

</header>