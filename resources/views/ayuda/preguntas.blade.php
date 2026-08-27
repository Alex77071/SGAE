@extends('layouts.app')

@section('title', 'SGAE - Preguntas frecuentes')

@section('content')

<section class="faq-page">

    <div class="faq-shell">

        {{-- =====================================================
             ENCABEZADO
        ====================================================== --}}

        <div class="faq-header">

            {{-- TÍTULO + BARRITA --}}
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
                        width="24"
                        height="24"
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
                            tengas conexión con el sistema. Intenta
                            nuevamente y, si el problema persiste,
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
                            asignados en Moodle. Cada usuario puede utilizar
                            únicamente las funciones para las que cuenta con
                            autorización.
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


                        {{-- SIGNO --}}
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
                                width="24"
                                height="24"
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

                                <path d="M11 17h2"></path>
                            </svg>

                        </span>

                        <span>
                            Ver manuales
                        </span>

                    </a>


                    {{-- CONTACTAR SOPORTE --}}
                    <a
                        href="{{ route('contacto.soporte') }}"
                        class="faq-help-button"
                    >

                        <span
                            class="faq-help-button__icon"
                            aria-hidden="true"
                        >

                            <svg
                                width="24"
                                height="24"
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

                    </a>

                </div>

            </aside>

        </div>

    </div>

</section>


{{-- =====================================================
     ESTILOS FAQ
====================================================== --}}

<style>

/* ==========================================================
   PÁGINA
========================================================== */

.faq-page {
    width: 100%;
    max-width: 1600px;

    margin: 0 auto;

    padding:
        clamp(18px, 2.5vh, 30px)
        clamp(22px, 4vw, 50px)
        clamp(22px, 3vh, 34px);

    overflow-x: hidden;
}


.faq-shell {
    width: 100%;

    padding: 0;

    background: transparent;

    border: none;
    border-radius: 0;

    box-shadow: none;
}


/* ==========================================================
   ENCABEZADO
========================================================== */

.faq-header {
    width: 100%;

    margin-bottom:
        clamp(18px, 2.5vh, 24px);

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 30px;
}


/* BARRITA + TÍTULO + DESCRIPCIÓN */

.faq-title-block {
    min-width: 0;

    padding-left: 14px;

    border-left:
        4px solid
        #823233;
}


.faq-title {
    margin: 0;

    color: #823233;

    font-size:
        clamp(
            24px,
            2vw,
            29px
        );

    line-height: 1.15;

    font-weight: 700;
}


.faq-subtitle {
    margin:
        7px
        0
        0;

    color: #343747;

    font-size:
        clamp(
            14px,
            1.2vw,
            16px
        );

    line-height: 1.35;
}


/* ==========================================================
   VOLVER AL INICIO
========================================================== */

.faq-back-button {
    width: 335px;
    min-height: 46px;

    padding:
        8px
        20px;

    border:
        1px solid
        #9f4949;

    border-radius: 10px;

    background: transparent;

    color: #823233 !important;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 12px;

    flex-shrink: 0;

    font-family: inherit;

    font-size: 17px;
    font-weight: 500;

    text-decoration: none !important;

    transition:
        background-color
        0.2s ease;
}


.faq-back-button:hover {
    background:
        rgba(
            130,
            50,
            51,
            0.06
        );
}


.faq-back-button__icon {
    width: 24px;
    min-width: 24px;
    height: 24px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 24px;
}


.faq-back-button__icon svg {
    width: 24px !important;
    height: 24px !important;

    display: block;
}


/* ==========================================================
   DOS COLUMNAS
========================================================== */

.faq-content {
    width: 100%;

    display: grid;

    grid-template-columns:
        minmax(0, 2.55fr)
        minmax(300px, 0.9fr);

    gap:
        clamp(
            18px,
            2vw,
            26px
        );

    /*
     * MUY IMPORTANTE:
     * Ayuda rápida NO se estira cuando
     * se abre una respuesta.
     */
    align-items: start;
}


/* ==========================================================
   ACORDEÓN
========================================================== */

.faq-accordion {
    width: 100%;
    min-width: 0;

    padding: 12px;

    display: flex;
    flex-direction: column;

    gap: 9px;

    border:
        1px solid
        #d7d4d4;

    border-radius: 20px;

    background: #ffffff;

    box-shadow:
        0
        3px
        10px
        rgba(
            0,
            0,
            0,
            0.04
        );
}


/* ==========================================================
   PREGUNTAS
========================================================== */

.faq-item {
    width: 100%;

    overflow: hidden;

    border:
        1px solid
        #d7d4d4;

    border-radius: 10px;

    background: #ffffff;

    transition:
        background-color
        0.2s ease;
}


/*
 * PREGUNTA SELECCIONADA:
 * solo rosa, SIN borde vino.
 */

.faq-item--active {
    background: #e6d3d2 !important;

    border-color:
        transparent !important;

    box-shadow:
        none !important;
}


.faq-question {
    width: 100%;

    min-height: 52px;

    padding:
        13px
        18px;

    border: none;

    background: transparent;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 16px;

    color: #343747;

    font-family: inherit;

    font-size:
        clamp(
            14px,
            1.2vw,
            16px
        );

    line-height: 1.25;

    font-weight: 600;

    text-align: left;

    cursor: pointer;
}


.faq-question:hover {
    color: #823233;
}


.faq-item--active
.faq-question {
    color: #823233;
}


.faq-question__text {
    min-width: 0;

    flex: 1;
}


.faq-icon {
    width: 26px;
    min-width: 26px;

    height: 26px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 26px;

    color: #823233;

    font-size: 25px;

    line-height: 1;

    font-weight: 400;
}


/* ==========================================================
   RESPUESTAS
========================================================== */

.faq-answer {
    padding:
        0
        48px
        16px
        18px;

    color: #343747;

    font-size: 15px;

    line-height: 1.5;
}


.faq-answer[hidden] {
    display: none !important;
}


.faq-answer p {
    margin: 0;
}


.faq-answer strong {
    color: #823233;
}


/* ==========================================================
   AYUDA RÁPIDA
========================================================== */

.faq-help-card {
    width: 100%;
    min-width: 0;

    padding:
        20px
        22px;

    display: flex;
    flex-direction: column;
    align-items: center;

    border:
        1px solid
        #d7d4d4;

    border-radius: 20px;

    background: #ffffff;

    box-shadow:
        0
        3px
        10px
        rgba(
            0,
            0,
            0,
            0.04
        );

    /*
     * Permanece arriba aunque
     * se abra otra pregunta.
     */
    align-self: start;

    position: sticky;
    top: 18px;
}


.faq-help-title {
    width: 100%;

    margin:
        0
        0
        4px;

    color: #823233;

    font-size: 21px;

    line-height: 1.15;

    font-weight: 700;

    text-align: left;
}


.faq-help-illustration {
    width: 176px;
    height: 132px;

    max-width: 100%;

    margin:
        4px
        auto
        5px;

    display: flex;
    align-items: center;
    justify-content: center;
}


.faq-help-illustration svg {
    width: 176px !important;
    height: 132px !important;

    max-width: 100%;

    display: block;
}


.faq-help-question {
    margin:
        4px
        0
        4px;

    color: #343747;

    font-size: 16px;

    line-height: 1.25;

    font-weight: 700;

    text-align: center;
}


.faq-help-text {
    margin:
        0
        0
        16px;

    color: #555560;

    font-size: 14px;

    line-height: 1.3;

    text-align: center;
}


.faq-help-actions {
    width: 100%;

    display: flex;
    flex-direction: column;

    gap: 10px;
}


/* ==========================================================
   BOTONES AYUDA
========================================================== */

.faq-help-button {
    width: 100%;

    min-height: 46px;

    padding:
        8px
        16px;

    border:
        1px solid
        #9f4949;

    border-radius: 10px;

    background: #ffffff;

    color: #823233 !important;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 11px;

    font-family: inherit;

    font-size: 15px;

    font-weight: 500;

    text-decoration: none !important;

    transition:
        background-color
        0.2s ease,
        color
        0.2s ease;
}


.faq-help-button:hover {
    background: #823233;

    color: #ffffff !important;
}


.faq-help-button__icon {
    width: 24px;
    min-width: 24px;

    height: 24px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 24px;
}


.faq-help-button__icon svg {
    width: 24px !important;
    height: 24px !important;

    display: block;
}


/* ==========================================================
   PANTALLAS CON POCA ALTURA
========================================================== */

@media
    (min-width: 993px)
    and (max-height: 760px) {

    .faq-page {
        padding-top: 14px;
        padding-bottom: 16px;
    }


    .faq-header {
        margin-bottom: 14px;
    }


    .faq-title {
        font-size: 24px;
    }


    .faq-subtitle {
        margin-top: 4px;

        font-size: 14px;
    }


    .faq-accordion {
        padding: 9px;

        gap: 7px;
    }


    .faq-question {
        min-height: 46px;

        padding:
            10px
            15px;

        font-size: 14px;
    }


    .faq-answer {
        padding:
            0
            40px
            12px
            15px;

        font-size: 14px;
    }


    .faq-help-card {
        padding:
            16px
            18px;
    }


    .faq-help-illustration {
        width: 145px;
        height: 105px;
    }


    .faq-help-illustration svg {
        width: 145px !important;
        height: 105px !important;
    }


    .faq-help-text {
        margin-bottom: 10px;
    }


    .faq-help-button {
        min-height: 41px;
    }

}


/* ==========================================================
   TABLET
========================================================== */

@media (max-width: 992px) {

    .faq-page {
        padding:
            22px
            24px
            32px;
    }


    .faq-content {
        grid-template-columns: 1fr;

        gap: 20px;
    }


    /*
     * En tablet deja de ser sticky
     * porque las tarjetas están
     * una debajo de la otra.
     */
    .faq-help-card {
        position: static;

        top: auto;
    }

}


/* ==========================================================
   CELULAR
========================================================== */

@media (max-width: 650px) {

    .faq-page {
        padding:
            18px
            14px
            28px;
    }


    .faq-header {
        flex-direction: column;
        align-items: flex-start;

        gap: 17px;

        margin-bottom: 18px;
    }


    .faq-title-block {
        padding-left: 11px;

        border-left-width: 4px;
    }


    .faq-title {
        font-size: 23px;
    }


    .faq-subtitle {
        font-size: 14px;
    }


    .faq-back-button {
        width: 100%;
        min-width: 0;

        min-height: 44px;

        font-size: 15px;
    }


    .faq-accordion {
        padding: 9px;

        gap: 8px;

        border-radius: 16px;
    }


    .faq-question {
        min-height: 50px;

        padding:
            12px
            13px;

        font-size: 14px;
    }


    .faq-answer {
        padding:
            0
            13px
            14px;

        font-size: 14px;
    }


    .faq-help-card {
        padding:
            18px
            16px;

        border-radius: 16px;
    }


    .faq-help-title {
        font-size: 20px;
    }


    .faq-help-button {
        min-height: 44px;

        font-size: 14px;
    }

}

</style>


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
                        | CERRAR TODAS
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
                        | ABRIR LA SELECCIONADA
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