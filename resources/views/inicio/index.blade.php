@extends('layouts.app')

@section('title', 'SGAE - Inicio')


@section('content')

<section class="dashboard">


    {{-- =====================================================
         BIENVENIDA
    ====================================================== --}}

    <div class="dashboard-welcome">

        <div class="dashboard-welcome__line"></div>

        <div>

            <h2 class="dashboard-welcome__title">

                ¡Bienvenido, {{ session('usuario') }}!

            </h2>

            <p class="dashboard-welcome__text">

                Desde aquí puedes descargar, analizar y consultar
                las evidencias de los exámenes realizados.

            </p>

        </div>

    </div>


    {{-- =====================================================
         EVIDENCIAS
    ====================================================== --}}

    <section class="dashboard-card evidence-card">

        <div class="dashboard-card__header">

            <span class="dashboard-card__icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >

                    <path
                        d="M3 6.5A2.5 2.5 0 0 1 5.5 4H9l2 2h7.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5Z"
                    ></path>

                </svg>

            </span>


            <div>

                <h3 class="dashboard-card__title">
                    Evidencias
                </h3>

                <p class="dashboard-card__description">

                    Descarga y analiza las evidencias de los<br>
                    exámenes de Moodle.

                </p>

            </div>

        </div>


        <div class="evidence-steps">


            {{-- PASO 1 --}}

            <article class="evidence-step">

                <span class="evidence-step__number">
                    1
                </span>


                <div class="evidence-step__content">

                    <button
                        type="button"
                        class="outline-action"
                    >

                        <span class="outline-action__icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path
                                    d="M3 6.5A2.5 2.5 0 0 1 5.5 4H9l2 2h7.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5Z"
                                ></path>

                            </svg>

                        </span>

                        Descargar carpetas

                    </button>


                    <p>

                        Obtenga las carpetas con las imágenes
                        descargadas

                    </p>

                </div>

            </article>


            {{-- PASO 2 --}}

            <article class="evidence-step">

                <span class="evidence-step__number">
                    2
                </span>


                <div class="evidence-step__content">

                    <button
                        type="button"
                        class="outline-action"
                    >

                       <span class="outline-action__icon" aria-hidden="true">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            >
                                <path d="M12 2v3"></path>
                                <path d="M12 19v3"></path>

                                <path d="M4.93 4.93l2.12 2.12"></path>
                                <path d="M16.95 16.95l2.12 2.12"></path>

                                <path d="M2 12h3"></path>
                                <path d="M19 12h3"></path>

                                <path d="M4.93 19.07l2.12-2.12"></path>
                                <path d="M16.95 7.05l2.12-2.12"></path>
                            </svg>

                        </span>

                        Analizar carpetas

                    </button>


                    <p>

                        Ejecute el análisis con Inteligencia Artificial y
                        consulte los resultados

                    </p>

                </div>

            </article>


            {{-- PASO 3 --}}

            <article class="evidence-step evidence-step--last">

                <span class="evidence-step__number">
                    3
                </span>


                <div class="evidence-step__content">

                    <button
                        type="button"
                        class="outline-action"
                    >

                       <span class="outline-action__icon" aria-hidden="true">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path d="M4 20L15 9"></path>

                                <path d="M13.5 7.5l3 3"></path>

                                <path d="M7 3v4"></path>
                                <path d="M5 5h4"></path>

                                <path d="M18 3v4"></path>
                                <path d="M16 5h4"></path>

                                <path d="M19 13v4"></path>
                                <path d="M17 15h4"></path>

                            </svg>

                        </span>

                        Historial de análisis

                    </button>


                    <p>

                        Consulta el historial de evidencias procesadas.

                    </p>

                </div>

            </article>

        </div>

    </section>


    {{-- =====================================================
         PARTE INFERIOR
    ====================================================== --}}

    <div class="dashboard-bottom">


        {{-- ESTADO DEL SISTEMA --}}

        <section class="dashboard-card status-card">

            <div class="dashboard-card__header dashboard-card__header--compact">

                <span class="dashboard-card__icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                    >

                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="13"
                            rx="1"
                        ></rect>

                        <path
                            d="M8 21h8"
                        ></path>

                        <path
                            d="M12 17v4"
                        ></path>

                    </svg>

                </span>


                <div>

                    <h3 class="dashboard-card__title">
                        Estado del sistema
                    </h3>

                    <p class="dashboard-card__description">

                        Resumen del estado actual del sistema

                    </p>

                </div>

            </div>


            <div class="status-summary">

                <strong>

                    12 carpetas pendientes de un total de 25

                </strong>

                <span>

                    Listas para ser analizadas

                </span>

            </div>

        </section>


        {{-- RECURSOS --}}

        <section class="dashboard-card resources-card">

            <div class="dashboard-card__header dashboard-card__header--compact">

                <span class="dashboard-card__icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <path
                            d="M2 5.5A3.5 3.5 0 0 1 5.5 2H11v17H5.5A3.5 3.5 0 0 0 2 22Z"
                        ></path>

                        <path
                            d="M22 5.5A3.5 3.5 0 0 0 18.5 2H13v17h5.5A3.5 3.5 0 0 1 22 22Z"
                        ></path>

                    </svg>

                </span>


                <div>

                    <h3 class="dashboard-card__title">
                        Recursos
                    </h3>

                    <p class="dashboard-card__description">

                        Consulta las guías y manuales de Moodle y del<br>
                        Sistema de Descarga de Evidencias.

                    </p>

                </div>

            </div>


<div class="resources-actions">

    <a
        href="{{ route('recursos.diagrama') }}"
        class="outline-action resources-action resources-action--link"
    >

        <span
            class="outline-action__icon"
            aria-hidden="true"
        >

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            >

                <rect
                    x="3"
                    y="4"
                    width="18"
                    height="16"
                    rx="2"
                ></rect>

                <path
                    d="M9.5 9a2.5 2.5 0 1 1 3.8 2.1c-.8.5-1.3 1-1.3 2"
                ></path>

                <path d="M12 16h.01"></path>

            </svg>

        </span>

        Conoce el flujo del proceso

    </a>


    <button
        type="button"
        class="outline-action resources-action"
    >

        <span class="outline-action__icon" aria-hidden="true">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            >

                <path
                    d="M3 5.5A3.5 3.5 0 0 1 6.5 2H11v17H6.5A3.5 3.5 0 0 0 3 22Z"
                ></path>

                <path
                    d="M21 5.5A3.5 3.5 0 0 0 17.5 2H13v17h4.5A3.5 3.5 0 0 1 21 22Z"
                ></path>

            </svg>

        </span>

                    Manuales de usuario

                </button>


            </div>

        </section>


    </div>

</section>

@endsection