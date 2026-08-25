@extends('layouts.app')

@section('title', 'SGAE - Manuales')

@section('content')

<section class="manuals-page">

    {{-- =====================================================
         ENCABEZADO DE LA PANTALLA
    ====================================================== --}}

    <div class="manuals-top">

        <div class="manuals-top__left">

            <nav class="manuals-breadcrumb" aria-label="Navegación">

                <span>Recursos</span>

                <span class="manuals-breadcrumb__separator">
                    &gt;
                </span>

                <span class="manuals-breadcrumb__current">
                    Manuales
                </span>

            </nav>


            <div class="manuals-heading">

                <div class="manuals-heading__line"></div>

                <div>

                    <h2 class="manuals-heading__title">
                        Manuales del sistema
                    </h2>

                    <p class="manuals-heading__description">
                        Consulte los manuales disponibles para comprender y utilizar la plataforma.
                    </p>

                </div>

            </div>

        </div>


        <a
            href="{{ route('inicio') }}"
            class="manuals-home-button"
        >

            <span class="manuals-home-button__icon" aria-hidden="true">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M3 11.5 12 4l9 7.5"></path>
                    <path d="M5.5 10.5V20h13v-9.5"></path>
                    <path d="M9.5 20v-6h5v6"></path>
                </svg>

            </span>

            <span>Volver al inicio</span>

        </a>

    </div>


    {{-- =====================================================
         CONTENIDO
    ====================================================== --}}

    <div class="manuals-content">

        {{-- =================================================
             LISTA DE MANUALES
        ================================================== --}}

        <div class="manuals-left">

            <div class="manuals-list">


                {{-- MANUAL 1 --}}

                <button
                    type="button"
                    class="manual-item manual-item--active"
                    data-manual
                    data-pdf="{{ asset('documentos/Introducción a Moodle.pdf') }}"
                    data-name="Introducción a Moodle"
                >

                    <span class="manual-item__icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 5.5A3.5 3.5 0 0 1 6.5 2H11v17H6.5A3.5 3.5 0 0 0 3 22Z"></path>
                            <path d="M21 5.5A3.5 3.5 0 0 0 17.5 2H13v17h4.5A3.5 3.5 0 0 1 21 22Z"></path>
                        </svg>

                    </span>

                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            01 — Introducción a Moodle
                        </span>

                        <span class="manual-item__description">
                            Conoce el propósito del sistema y los conceptos básicos para comenzar a utilizarlo
                        </span>

                    </span>

                </button>


                {{-- MANUAL 2 --}}

                <button
                    type="button"
                    class="manual-item"
                    data-manual
                    data-pdf="{{ asset('documentos/Administración y permisos.pdf') }}"
                    data-name="Administración y permisos"
                >

                    <span class="manual-item__icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="9" cy="8" r="3"></circle>
                            <path d="M3.5 20v-1.5A5.5 5.5 0 0 1 9 13h1"></path>
                            <circle cx="16.5" cy="9" r="2.5"></circle>
                            <path d="M14 14h1.5a5 5 0 0 1 5 5v1"></path>
                        </svg>

                    </span>

                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            02 — Administración y permisos
                        </span>

                        <span class="manual-item__description">
                            Aprenda a gestionar usuarios, roles y permisos dentro del sistema.
                        </span>

                    </span>

                </button>


                {{-- MANUAL 3 --}}

                <button
                    type="button"
                    class="manual-item"
                    data-manual
                    data-pdf="{{ asset('documentos/Creación y configuración de cursos.pdf') }}"
                    data-name="Creación y configuración de cursos"
                >

                    <span class="manual-item__icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m2 9 10-5 10 5-10 5Z"></path>
                            <path d="M6 11.5V17c3.5 2.5 8.5 2.5 12 0v-5.5"></path>
                            <path d="M22 9v6"></path>
                        </svg>

                    </span>

                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            03 — Creación y configuración de cursos
                        </span>

                        <span class="manual-item__description">
                            Guía para crear cursos y configurar sus opciones principales en el sistema.
                        </span>

                    </span>

                </button>


                {{-- MANUAL 4 --}}

                <button
                    type="button"
                    class="manual-item"
                    data-manual
                    data-pdf="{{ asset('documentos/Creación de exámenes.pdf') }}"
                    data-name="Creación de exámenes"
                >

                    <span class="manual-item__icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M6 2h8l4 4v16H6Z"></path>
                            <path d="M14 2v5h5"></path>
                            <path d="M9 13h6"></path>
                            <path d="M9 17h6"></path>
                        </svg>

                    </span>

                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            04 — Creación de exámenes
                        </span>

                        <span class="manual-item__description">
                            Instrucciones para configurar y publicar exámenes en Moodle.
                        </span>

                    </span>

                </button>


                {{-- MANUAL 5 --}}

                <button
                    type="button"
                    class="manual-item"
                    data-manual
                    data-pdf="{{ asset('documentos/Banco de preguntas y configuración.pdf') }}"
                    data-name="Banco de preguntas y configuración"
                >

                    <span class="manual-item__icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M9.8 9a2.4 2.4 0 1 1 3.9 1.9c-1 .7-1.7 1.2-1.7 2.6"></path>
                            <path d="M12 17h.01"></path>
                        </svg>

                    </span>

                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            05 — Banco de preguntas y configuración
                        </span>

                        <span class="manual-item__description">
                            Aprenda a gestionar el banco de preguntas y configurar opciones de evaluación.
                        </span>

                    </span>

                </button>

            </div>


            {{-- MENSAJE INFORMATIVO --}}

            <div class="manuals-info">

                <span class="manuals-info__icon" aria-hidden="true">

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
                    Selecciona un manual para ver su vista previa.
                </span>

            </div>

        </div>


        {{-- =================================================
             VISTA PREVIA
        ================================================== --}}

        <div class="manuals-right">

            <section class="manual-preview-card">

                <div class="manual-preview-card__header">

                    <h3>
                        Vista previa del manual
                    </h3>

                    <span class="manual-version">
                        Versión 1.0
                    </span>

                </div>


                <div class="manual-preview-card__viewer">

                    <iframe
                        id="manualPreview"
                        src="{{ asset('documentos/Introducción a Moodle.pdf') }}#page=1&zoom=page-width&toolbar=0&navpanes=0"
                        title="Vista previa del manual"
                    ></iframe>

                </div>

            </section>


            <a
                id="manualDownload"
                href="{{ asset('documentos/Introducción a Moodle.pdf') }}"
                class="manual-download"
                download
            >

                <span class="manual-download__icon">

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
                        <path d="M5 20h14"></path>
                    </svg>

                </span>

                <span>
                    Descargar
                </span>

            </a>

        </div>

    </div>

</section>

@endsection