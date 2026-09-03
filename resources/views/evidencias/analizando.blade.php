@extends('layouts.app')

@section('title', 'SGAE - Analizando evidencias')

@section('content')

<section
    class="analysis-process-page"
    id="analysisProcessPage"
    data-progress-url="{{ route('evidencias.analisis.progreso') }}"
>

    {{-- =====================================================
         ESTADO: ANALIZANDO
    ====================================================== --}}

    <div
        class="analysis-process-card"
        id="analysisRunning"
    >

        <h2 class="analysis-process-title">
            Analizando evidencias...
        </h2>


        {{-- =================================================
             ARCHIVO SELECCIONADO
        ================================================== --}}

        <div class="analysis-process-exam">

            <strong>
                {{
                    session(
                        'analisis_archivo',
                        'Archivo seleccionado'
                    )
                }}
            </strong>

            <span>
                Archivo seleccionado para análisis
            </span>

        </div>


        {{-- =================================================
             PROGRESO CIRCULAR
        ================================================== --}}

        <div class="analysis-circle">

            <svg
                viewBox="0 0 120 120"
                aria-hidden="true"
            >

                <circle
                    class="analysis-circle__track"
                    cx="60"
                    cy="60"
                    r="50"
                ></circle>

                <circle
                    class="analysis-circle__progress"
                    id="analysisCircleProgress"
                    cx="60"
                    cy="60"
                    r="50"
                ></circle>

            </svg>


            <span
                class="analysis-circle__percentage"
                id="analysisPercentage"
            >
                0 %
            </span>

        </div>


        {{-- =================================================
             CONTADOR DE IMÁGENES
        ================================================== --}}

        <p
            class="analysis-image-counter"
            id="analysisImageCounter"
        >
            Imagen 0 de 0
        </p>


        {{-- =================================================
             BARRA DE PROGRESO
        ================================================== --}}

        <div class="analysis-progress">

            <div class="analysis-progress__track">

                <div
                    class="analysis-progress__bar"
                    id="analysisProgressBar"
                ></div>

            </div>


            <span
                class="analysis-current-file"
                id="analysisCurrentFile"
            >
                Preparando análisis...
            </span>

        </div>


        {{-- =================================================
             AVISO
        ================================================== --}}

        <div class="analysis-warning">

            <span
                class="analysis-warning__icon"
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

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    ></circle>

                    <path d="M12 7v6"></path>

                    <path d="M12 17h.01"></path>

                </svg>

            </span>


            <span>
                No cierre la ventana mientras el análisis está en proceso.
            </span>

        </div>

    </div>


    {{-- =====================================================
         ESTADO: ANÁLISIS COMPLETADO
    ====================================================== --}}

    <div
        class="analysis-finished-card"
        id="analysisComplete"
        hidden
    >

        {{-- =================================================
             ICONO CHECK
        ================================================== --}}

        <div
            class="analysis-finished-icon"
            aria-hidden="true"
        >

            <svg
                viewBox="0 0 120 120"
                fill="none"
                stroke="currentColor"
                stroke-width="6"
                stroke-linecap="round"
                stroke-linejoin="round"
            >

                <circle
                    cx="60"
                    cy="60"
                    r="48"
                ></circle>

                <path
                    d="M34 59L52 77L87 42"
                ></path>

            </svg>

        </div>


        {{-- =================================================
             TÍTULO
        ================================================== --}}

        <h2 class="analysis-finished-title">
            ¡Análisis completado!
        </h2>


        {{-- =================================================
             RESUMEN DEL ANÁLISIS
        ================================================== --}}

        <div class="analysis-finished-summary">

            {{-- ARCHIVO ANALIZADO --}}

            <div
                class="
                    analysis-finished-summary__item
                    analysis-finished-summary__item--exam
                "
            >

                <strong>
                    {{
                        session(
                            'analisis_archivo',
                            'Archivo seleccionado'
                        )
                    }}
                </strong>

                <span>
                    Archivo analizado
                </span>

            </div>


            <div
                class="analysis-finished-summary__divider"
                aria-hidden="true"
            ></div>


            {{-- IMÁGENES ANALIZADAS --}}

            <div class="analysis-finished-summary__item">

                <strong id="analysisFinishedImages">
                    0
                </strong>

                <span>
                    Imágenes
                </span>

            </div>

        </div>


        {{-- =================================================
             MENSAJE
        ================================================== --}}

        <div class="analysis-finished-message">

            <span
                class="analysis-finished-message__icon"
                aria-hidden="true"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >

                    <path d="M5 12l4 4L19 6"></path>

                </svg>

            </span>


            <span>
                Ya puede consultar los resultados del análisis.
            </span>

        </div>


        {{-- =================================================
             ACCIONES
        ================================================== --}}

        <div class="analysis-finished-actions">

            {{-- VER RESULTADOS --}}

            <a
                href="{{ route('evidencias.resultados') }}"
                class="
                    analysis-finished-button
                    analysis-finished-button--primary
                "
            >

                <span
                    class="analysis-finished-button__icon"
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

                        <path d="M4 19V5"></path>

                        <path d="M4 19H20"></path>

                        <path d="M7 15l4-4 3 2 5-6"></path>

                    </svg>

                </span>


                <span>
                    Ver resultados
                </span>

            </a>


            {{-- ANALIZAR OTRO ARCHIVO --}}

            <a
                href="{{ route('evidencias.analizar') }}"
                class="
                    analysis-finished-button
                    analysis-finished-button--secondary
                "
            >

                <span
                    class="analysis-finished-button__icon"
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

                        <rect
                            x="5"
                            y="3"
                            width="14"
                            height="18"
                            rx="1"
                        ></rect>

                        <path d="M9 8h6"></path>

                        <path d="M9 12h6"></path>

                        <path d="M9 16h6"></path>

                    </svg>

                </span>


                <span>
                    Analizar otro archivo
                </span>

            </a>

        </div>

    </div>

</section>

@endsection