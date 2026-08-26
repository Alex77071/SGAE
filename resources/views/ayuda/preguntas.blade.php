@extends('layouts.app')

@section('title', 'SGAE - Preguntas frecuentes')

@section('content')

<section class="faq-page">

    <div class="faq-shell">

        {{-- =====================================================
             ENCABEZADO
        ====================================================== --}}

        <div class="faq-header">

            <div class="faq-title-block">

                <h2 class="faq-title">
                    Preguntas frecuentes
                </h2>

                <p class="faq-subtitle">
                    Resuelve las dudas más comunes sobre el sistema.
                </p>

            </div>


            {{-- VOLVER AL INICIO --}}
            <a
                href="{{ route('inicio') }}"
                class="faq-back-button"
            >

                <span
                    class="faq-back-button__icon"
                    aria-hidden="true"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M3 11.5 12 4l9 7.5"></path>

                        <path
                            d="M5.5 10.5V20h5v-6h3v6h5v-9.5"
                        ></path>
                    </svg>
                </span>

                <span>
                    Volver al inicio
                </span>

            </a>

        </div>


        {{-- =====================================================
             CONTENIDO PRINCIPAL
        ====================================================== --}}

        <div class="faq-content">


            {{-- =================================================
                 PREGUNTAS
            ================================================== --}}

            <div
                class="faq-accordion"
                id="faqAccordion"
            >


                {{-- PREGUNTA 1 --}}
                {{-- PREGUNTA 1 --}}
<div class="faq-item">

    <button
        type="button"
        class="faq-question"
        aria-expanded="false"
    >

        <span class="faq-question__text">
            ¿Cómo descargo carpetas?
        </span>

        <span
            class="faq-icon"
            aria-hidden="true"
        >
            +
        </span>

    </button>


    <div
        class="faq-answer"
        hidden
    >

        <p>
            En el dashboard, selecciona
            <strong>“Descargar carpetas”</strong>.
            El sistema generará las carpetas con las
            imágenes disponibles para análisis.
        </p>

    </div>

</div>

                {{-- PREGUNTA 2 --}}
                <div class="faq-item">

                    <button
                        type="button"
                        class="faq-question"
                        aria-expanded="false"
                    >

                        <span class="faq-question__text">
                            ¿Cómo analizo evidencias?
                        </span>

                        <span
                            class="faq-icon"
                            aria-hidden="true"
                        >
                            +
                        </span>

                    </button>


                    <div
                        class="faq-answer"
                        hidden
                    >

                        <p>
                            Desde la pantalla principal selecciona
                            <strong>“Analizar carpetas”</strong>.
                            Después elige la evidencia que deseas
                            procesar para iniciar el análisis.
                        </p>

                    </div>

                </div>


                {{-- PREGUNTA 3 --}}
                <div class="faq-item">

                    <button
                        type="button"
                        class="faq-question"
                        aria-expanded="false"
                    >

                        <span class="faq-question__text">
                            ¿Dónde veo los resultados?
                        </span>

                        <span
                            class="faq-icon"
                            aria-hidden="true"
                        >
                            +
                        </span>

                    </button>


                    <div
                        class="faq-answer"
                        hidden
                    >

                        <p>
                            Los resultados pueden consultarse desde
                            <strong>“Historial de análisis”</strong>,
                            disponible en la pantalla principal.
                        </p>

                    </div>

                </div>


                {{-- PREGUNTA 4 --}}
                <div class="faq-item">

                    <button
                        type="button"
                        class="faq-question"
                        aria-expanded="false"
                    >

                        <span class="faq-question__text">
                            ¿Qué hago si hay un error?
                        </span>

                        <span
                            class="faq-icon"
                            aria-hidden="true"
                        >
                            +
                        </span>

                    </button>


                    <div
                        class="faq-answer"
                        hidden
                    >

                        <p>
                            Verifica que tu sesión continúe activa y que
                            tengas conexión con el sistema.
                            Intenta nuevamente y, si el problema persiste,
                            utiliza
                            <strong>“Contactar soporte”</strong>.
                        </p>

                    </div>

                </div>


                {{-- PREGUNTA 5 --}}
                <div class="faq-item">

                    <button
                        type="button"
                        class="faq-question"
                        aria-expanded="false"
                    >

                        <span class="faq-question__text">
                            ¿Cómo subo evidencias al sistema?
                        </span>

                        <span
                            class="faq-icon"
                            aria-hidden="true"
                        >
                            +
                        </span>

                    </button>


                    <div
                        class="faq-answer"
                        hidden
                    >

                        <p>
                            Las evidencias utilizadas por SGAE se obtienen
                            de los exámenes realizados en Moodle.
                            El sistema descarga y organiza las imágenes
                            disponibles para posteriormente analizarlas.
                        </p>

                    </div>

                </div>


                {{-- PREGUNTA 6 --}}
                <div class="faq-item">

                    <button
                        type="button"
                        class="faq-question"
                        aria-expanded="false"
                    >

                        <span class="faq-question__text">
                            ¿Quién puede administrar usuarios y permisos?
                        </span>

                        <span
                            class="faq-icon"
                            aria-hidden="true"
                        >
                            +
                        </span>

                    </button>


                    <div
                        class="faq-answer"
                        hidden
                    >

                        <p>
                            El acceso depende de la cuenta y de los permisos
                            asignados en Moodle.
                            Cada usuario puede utilizar únicamente las
                            funciones para las que cuenta con autorización.
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 AYUDA RÁPIDA
            ================================================== --}}

            <aside class="faq-help-card">

                <h3 class="faq-help-title">
                    Ayuda rápida
                </h3>


                {{-- ILUSTRACIÓN --}}
                <div
                    class="faq-help-illustration"
                    aria-hidden="true"
                >

                    <svg
                        width="176"
                        height="132"
                        viewBox="0 0 190 150"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >

                        {{-- FONDO ROSA --}}
                        <circle
                            cx="118"
                            cy="58"
                            r="43"
                            fill="#E6D3D2"
                            opacity="0.72"
                        ></circle>


                        {{-- COMPUTADORA --}}
                        <rect
                            x="38"
                            y="52"
                            width="88"
                            height="60"
                            rx="5"
                            fill="#FFFFFF"
                            stroke="#343747"
                            stroke-width="2"
                        ></rect>


                        <rect
                            x="45"
                            y="59"
                            width="74"
                            height="45"
                            rx="2"
                            fill="#FAF7F7"
                            stroke="#343747"
                            stroke-width="1.5"
                        ></rect>


                        {{-- BASE --}}
                        <path
                            d="M29 117H136"
                            stroke="#343747"
                            stroke-width="2"
                            stroke-linecap="round"
                        ></path>


                        <path
                            d="M48 117L55 123H109L116 117"
                            stroke="#343747"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>


                        {{-- BURBUJA --}}
                        <path
                            d="M124 28C140 28 152 39 152 54C152 69 140 80 124 80H116L104 91L106 76C99 71 96 63 96 54C96 39 108 28 124 28Z"
                            fill="#FFFFFF"
                            stroke="#823233"
                            stroke-width="2"
                        ></path>


                        {{-- SIGNO DE PREGUNTA --}}
                        <text
                            x="124"
                            y="65"
                            text-anchor="middle"
                            font-size="32"
                            fill="#823233"
                            font-family="Arial, sans-serif"
                        >
                            ?
                        </text>


                        {{-- DECORACIÓN --}}
                        <path
                            d="M165 61V73"
                            stroke="#823233"
                            stroke-width="2"
                            stroke-linecap="round"
                        ></path>


                        <path
                            d="M159 67H171"
                            stroke="#823233"
                            stroke-width="2"
                            stroke-linecap="round"
                        ></path>

                    </svg>

                </div>


                <h4 class="faq-help-question">
                    ¿No encontraste tu respuesta?
                </h4>


                <p class="faq-help-text">
                    Estamos aquí para ayudarte.
                </p>


                <div class="faq-help-actions">


                    {{-- VER MANUALES --}}
                    <a
                        href="{{ route('manuales') }}"
                        class="faq-help-button"
                    >

                        <span
                            class="faq-help-button__icon"
                            aria-hidden="true"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path
                                    d="M4 19.5A2.5 2.5 0 0 1 6.5 17H11V5.5A2.5 2.5 0 0 0 8.5 3H4Z"
                                ></path>

                                <path
                                    d="M20 19.5A2.5 2.5 0 0 0 17.5 17H13V5.5A2.5 2.5 0 0 1 15.5 3H20Z"
                                ></path>

                                <path
                                    d="M11 17h2"
                                ></path>

                            </svg>

                        </span>

                        <span>
                            Ver manuales
                        </span>

                    </a>


                    {{-- CONTACTAR SOPORTE --}}
                    <button
                        type="button"
                        class="faq-help-button"
                        id="faqContactSupport"
                    >

                        <span
                            class="faq-help-button__icon"
                            aria-hidden="true"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
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
                            Contactar soporte
                        </span>

                    </button>

                </div>

            </aside>

        </div>


        

</section>


{{-- =====================================================
     FUNCIONAMIENTO DEL ACORDEÓN
====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const preguntas =
            document.querySelectorAll(
                '.faq-question'
            );


        preguntas.forEach(
            function (pregunta) {

                pregunta.addEventListener(
                    'click',
                    function () {

                        const item =
                            pregunta.closest(
                                '.faq-item'
                            );


                        const estaAbierta =
                            pregunta.getAttribute(
                                'aria-expanded'
                            ) === 'true';


                        /*
                        |--------------------------------------------------------------------------
                        | CERRAR TODAS LAS PREGUNTAS
                        |--------------------------------------------------------------------------
                        */

                        document
                            .querySelectorAll(
                                '.faq-item'
                            )
                            .forEach(
                                function (otroItem) {

                                    const otraPregunta =
                                        otroItem.querySelector(
                                            '.faq-question'
                                        );


                                    const otraRespuesta =
                                        otroItem.querySelector(
                                            '.faq-answer'
                                        );


                                    const otroIcono =
                                        otroItem.querySelector(
                                            '.faq-icon'
                                        );


                                    otroItem.classList.remove(
                                        'faq-item--active'
                                    );


                                    otraPregunta.setAttribute(
                                        'aria-expanded',
                                        'false'
                                    );


                                    otraRespuesta.hidden =
                                        true;


                                    otroIcono.textContent =
                                        '+';

                                }
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | ABRIR LA PREGUNTA SELECCIONADA
                        |--------------------------------------------------------------------------
                        */

                        if (!estaAbierta) {

                            const respuesta =
                                item.querySelector(
                                    '.faq-answer'
                                );


                            const icono =
                                item.querySelector(
                                    '.faq-icon'
                                );


                            item.classList.add(
                                'faq-item--active'
                            );


                            pregunta.setAttribute(
                                'aria-expanded',
                                'true'
                            );


                            respuesta.hidden =
                                false;


                            icono.textContent =
                                '−';

                        }

                    }
                );

            }
        );

    }
);

</script>

@endsection