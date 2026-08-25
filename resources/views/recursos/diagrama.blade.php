@extends('layouts.app')

@section('title', 'SGAE - Diagrama del proceso')

@section('content')

<section class="diagram-page">

    {{-- =====================================================
         MIGAS DE PAN
    ====================================================== --}}
    <nav
        class="diagram-breadcrumb"
        aria-label="Navegación"
    >
        <span>Recursos</span>

        <span
            class="diagram-breadcrumb__separator"
            aria-hidden="true"
        >
            ›
        </span>

        <span class="diagram-breadcrumb__current">
            Diagrama
        </span>
    </nav>


    {{-- =====================================================
         ENCABEZADO DE LA PÁGINA
    ====================================================== --}}
    <div class="diagram-page__header">

        <div class="diagram-heading">

            <div class="diagram-heading__line"></div>

            <div class="diagram-heading__content">

                <h2 class="diagram-heading__title">
                    Diagrama del proceso
                </h2>

                <p class="diagram-heading__description">
                    Consulta el flujo general para la gestión y análisis de evidencias.
                </p>

            </div>

        </div>


        <a
            href="{{ route('inicio') }}"
            class="diagram-back-button"
        >
            <span class="diagram-back-button__icon">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="M3 11.5 12 4l9 7.5"></path>
                    <path d="M5.5 10.5V20h5v-6h3v6h5v-9.5"></path>
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
    <div class="diagram-grid">


        {{-- =================================================
             INFORMACIÓN DEL DIAGRAMA
        ================================================== --}}
        <aside class="diagram-info-card">

            <div class="diagram-card-heading">

                <span class="diagram-card-heading__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 11v5"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                </span>

                <h3 class="diagram-card-heading__title">
                    Información del diagrama
                </h3>

            </div>


            <div class="diagram-card-divider"></div>


            {{-- DESCRIPCIÓN --}}
            <div class="diagram-type">

                <span class="diagram-type__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <rect x="9" y="2" width="6" height="5"></rect>

                        <rect
                            x="2"
                            y="17"
                            width="6"
                            height="5"
                        ></rect>

                        <rect
                            x="9"
                            y="17"
                            width="6"
                            height="5"
                        ></rect>

                        <rect
                            x="16"
                            y="17"
                            width="6"
                            height="5"
                        ></rect>

                        <path d="M12 7v5"></path>
                        <path d="M5 12h14"></path>
                        <path d="M5 12v5"></path>
                        <path d="M12 12v5"></path>
                        <path d="M19 12v5"></path>
                    </svg>
                </span>


                <div class="diagram-type__content">

                    <h4>
                        Diagrama de actividades
                    </h4>

                    <p>
                        Representa el flujo principal para la
                        gestión y análisis de evidencias en el
                        sistema.
                    </p>

                </div>

            </div>


            <div class="diagram-card-divider"></div>


            {{-- VERSIÓN --}}
            <div class="diagram-version">

                <span class="diagram-version__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </span>

                <strong>
                    Versión:
                </strong>

                <span>
                    1.0
                </span>

            </div>


            {{-- DESCARGAR --}}
            <a
                href="{{ asset('documentos/diagrama_proceso.pdf') }}"
                class="diagram-download-button"
                download="diagrama_proceso.pdf"
            >
                <span class="diagram-download-button__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 20h14"></path>
                    </svg>
                </span>

                <span>
                    Descargar
                </span>
            </a>

        </aside>


        {{-- =================================================
             VISTA DEL DIAGRAMA
        ================================================== --}}
        <section class="diagram-view-card">

            <div class="diagram-card-heading">

                <span
                    class="diagram-card-heading__icon
                           diagram-card-heading__icon--monitor"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <rect
                            x="3"
                            y="3"
                            width="18"
                            height="13"
                            rx="1"
                        ></rect>

                        <path d="M8 21h8"></path>
                        <path d="M12 16v5"></path>
                    </svg>
                </span>

                <h3 class="diagram-card-heading__title">
                    Vista del diagrama
                </h3>

            </div>


            <div
                class="diagram-viewer"
                id="diagramViewer"
            >

                <iframe
                    src="{{ asset('documentos/diagrama_proceso.pdf') }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                    class="diagram-viewer__pdf"
                    title="Diagrama del proceso"
                ></iframe>


                <button
                    type="button"
                    class="diagram-fullscreen-button"
                    id="diagramFullscreenButton"
                    aria-label="Ver diagrama en pantalla completa"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M8 3H3v5"></path>
                        <path d="M16 3h5v5"></path>
                        <path d="M8 21H3v-5"></path>
                        <path d="M16 21h5v-5"></path>
                    </svg>
                </button>

            </div>

        </section>

    </div>


    {{-- =====================================================
         MENSAJE INFERIOR
    ====================================================== --}}
    <div class="diagram-notice">

        <span class="diagram-notice__icon">
            i
        </span>

        <span>
            Este diagrama le ayudará a comprender el flujo principal del sistema.
        </span>

    </div>

</section>

@endsection