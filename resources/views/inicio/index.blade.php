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

                        <span class="outline-action__icon">
                            ✣
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

                        <span class="outline-action__icon">
                            ✣
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


                <button
                    type="button"
                    class="outline-action resources-action"
                >

                    <span>
                        ?
                    </span>

                    Conoce el flujo del proceso

                </button>


                <button
                    type="button"
                    class="outline-action resources-action"
                >

                    <span>
                        ♧
                    </span>

                    Manuales de usuario

                </button>


            </div>

        </section>


    </div>

</section>

@endsection