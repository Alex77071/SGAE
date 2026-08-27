@extends('layouts.app')

@section('title', 'SGAE - Resultado del análisis')

@section('content')

<section class="analysis-result-page">

    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <nav
        class="analysis-result-breadcrumb"
        aria-label="Navegación"
    >

        <span>
            Resultados
        </span>

        <span
            class="analysis-result-breadcrumb__separator"
            aria-hidden="true"
        >
            ›
        </span>

        <span class="analysis-result-breadcrumb__current">
            Detalle
        </span>

    </nav>


    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="analysis-result-header">

        <div class="analysis-result-heading">

            <div class="analysis-result-heading__line"></div>

            <div>

                <h2 class="analysis-result-heading__title">
                    Resultado del análisis
                </h2>

                <p class="analysis-result-heading__description">
                    Consulta la vista previa del reporte generado y descárgalo si lo necesitas.
                </p>

            </div>

        </div>


        <a
            href="{{ route('inicio') }}"
            class="analysis-result-back"
        >

            <span
                class="analysis-result-back__icon"
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
                    <path d="M3 11.5L12 4l9 7.5"></path>
                    <path d="M5.5 10v10h13V10"></path>
                    <path d="M9.5 20v-6h5v6"></path>
                </svg>
            </span>

            <span>
                Volver al inicio
            </span>

        </a>

    </div>


    {{-- =====================================================
         CONTENIDO
    ====================================================== --}}

    <div class="analysis-result-grid">


        {{-- =================================================
             INFORMACIÓN DEL RESULTADO
        ================================================== --}}

        <aside class="analysis-result-info">

            <div class="analysis-result-card-heading">

                <span
                    class="analysis-result-card-heading__icon"
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
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 11v6"></path>
                        <path d="M12 7h.01"></path>
                    </svg>
                </span>

                <h3>
                    Información del resultado
                </h3>

            </div>


            <div class="analysis-result-divider"></div>


            {{-- EXAMEN --}}

            <div class="analysis-result-info-row">

                <div class="analysis-result-info-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <path d="M14 2v6h6"></path>
                        <path d="M8 13h8"></path>
                        <path d="M8 17h5"></path>
                    </svg>

                </div>

                <div>

                    <strong>
                        Examen final
                    </strong>

                    <p>
                        Programación
                        <span>|</span>
                        Grupo A
                    </p>

                </div>

            </div>


            {{-- FECHA --}}

            <div class="analysis-result-detail">

                <strong>
                    Fecha:
                </strong>

                <span>
                    12 de agosto de 2026
                </span>

            </div>


            {{-- ESTADO --}}

            <div class="analysis-result-detail">

                <strong>
                    Estado:
                </strong>

                <span>
                    Completado
                </span>

            </div>


            <div class="analysis-result-divider"></div>


            {{-- ARCHIVOS --}}

            <div class="analysis-result-info-row">

                <div class="analysis-result-info-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
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

                        <circle cx="8.5" cy="9" r="1.5"></circle>

                        <path d="m21 15-5-5L5 20"></path>
                    </svg>

                </div>

                <div>

                    <strong>
                        32 archivos de alumnos analizados
                    </strong>

                    <p>
                        Imágenes analizadas
                    </p>

                </div>

            </div>


            <div class="analysis-result-divider"></div>


            {{-- ESTADÍSTICAS --}}

            <div class="analysis-result-info-row">

                <div class="analysis-result-info-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M4 19V9"></path>
                        <path d="M9 19V5"></path>
                        <path d="M14 19v-7"></path>
                        <circle cx="18" cy="8" r="3"></circle>
                        <path d="m20.5 10.5 2 2"></path>
                    </svg>

                </div>

                <div>

                    <strong>
                        Estadísticas
                    </strong>

                    <p>
                        Nivel de confianza:
                        Regular
                        <span>|</span>
                        70%
                    </p>

                </div>

            </div>


            {{-- DESCARGAR --}}

            <a
                href="{{ route('evidencias.reporte.prueba') }}"
                class="analysis-result-download"
            >

                <span
                    class="analysis-result-download__icon"
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
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>
                </span>

                <span>
                    Descargar PDF
                </span>

            </a>

        </aside>


        {{-- =================================================
             VISTA PREVIA
        ================================================== --}}

        <section class="analysis-result-preview">

            <div class="analysis-result-card-heading">

                <span
                    class="analysis-result-card-heading__icon"
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
                            x="3"
                            y="4"
                            width="18"
                            height="14"
                            rx="2"
                        ></rect>

                        <path d="M8 21h8"></path>
                        <path d="M12 18v3"></path>
                    </svg>
                </span>

                <h3>
                    Vista previa del resultado
                </h3>

            </div>


           <div
    class="analysis-result-viewer"
    id="analysisResultViewer"
>

    <button
        type="button"
        class="analysis-result-viewer__fullscreen"
        id="analysisResultFullscreen"
        aria-label="Ver PDF en pantalla completa"
        title="Pantalla completa"
    >
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M8 3H3v5"></path>
            <path d="M16 3h5v5"></path>
            <path d="M8 21H3v-5"></path>
            <path d="M16 21h5v-5"></path>
        </svg>
    </button>

    <iframe
        src="{{ route('evidencias.reporte.prueba') }}"
        title="Vista previa del reporte de análisis"
        class="analysis-result-viewer__frame"
    ></iframe>

</div>

        </section>

    </div>


    {{-- =====================================================
         AVISO
    ====================================================== --}}

    <div class="analysis-result-notice">

        <span
            class="analysis-result-notice__icon"
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
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M12 11v6"></path>
                <path d="M12 7h.01"></path>
            </svg>
        </span>

        <span>
            Los resultados generados por la IA son orientativos y deben ser revisados y supervisados por el profesor antes de tomar cualquier decisión.
        </span>

    </div>

</section>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const viewer =
        document.getElementById('analysisResultViewer');

    const fullscreenButton =
        document.getElementById('analysisResultFullscreen');


    if (!viewer || !fullscreenButton) {
        return;
    }


    fullscreenButton.addEventListener('click', function () {

        if (!document.fullscreenElement) {

            viewer.requestFullscreen();

        } else {

            document.exitFullscreen();

        }

    });

});

</script>

@endsection