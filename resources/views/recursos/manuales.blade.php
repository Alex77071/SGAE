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
                        Manuales
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
                {{-- =========================================================
                    MANUAL 6 - MANUAL PARA ALUMNOS
                ========================================================= --}}

                <button
                    type="button"
                    class="manual-item"
                    data-manual
                    data-type="pdf"
                    data-pdf="{{ asset('documentos/Manual para alumnos.pdf') }}"
                    data-name="Manual para alumnos"
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
                            <circle cx="12" cy="8" r="3"></circle>

                            <path
                                d="M6 20v-1.5A5.5 5.5 0 0 1 11.5 13h1A5.5 5.5 0 0 1 18 18.5V20"
                            ></path>

                            <path d="M4 4h5"></path>
                            <path d="M4 7h4"></path>
                        </svg>

                    </span>


                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            06 — Manual para alumnos
                        </span>

                        <span class="manual-item__description">
                            Guía para personalizar el manual de acceso del alumno a Moodle.
                        </span>

                    </span>

                </button>




          {{-- =========================================================
                    MANUAL 7 - MANUAL DEL USO DEL SISTEMA
                ========================================================= --}}

                <button
                    type="button"
                    class="manual-item"
                    data-manual
                    data-type="pdf"
                    data-pdf="{{ asset('documentos/Manual del uso del sistema.pdf') }}"
                    data-name="Manual del uso del sistema"
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
                            <path d="M9 12h6"></path>
                            <path d="M9 16h6"></path>
                        </svg>

                    </span>


                    <span class="manual-item__content">

                        <span class="manual-item__title">
                            07 — Manual del uso del sistema
                        </span>

                        <span class="manual-item__description">
                            Guía general para conocer el uso y funcionamiento del sistema.
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

                   <span
                        class="manual-version"
                        id="manualVersion"
                    >
                        Versión 1.0
                    </span>

                </div>


                <div
                    class="manual-preview-card__viewer"
                    id="manualPreviewViewer"
                >

                    {{-- BOTÓN PARA EXPANDIR LA VISTA PREVIA --}}
                    <button
                        type="button"
                        class="manual-preview-fullscreen"
                        id="manualPreviewFullscreen"
                        aria-label="Ver manual en pantalla completa"
                        title="Pantalla completa"
                    >
                        <svg
                            class="manual-preview-fullscreen__icon manual-preview-fullscreen__icon--expand"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M8 3H5a2 2 0 0 0-2 2v3"></path>
                            <path d="M16 3h3a2 2 0 0 1 2 2v3"></path>
                            <path d="M8 21H5a2 2 0 0 1-2-2v-3"></path>
                            <path d="M16 21h3a2 2 0 0 0 2-2v-3"></path>
                        </svg>

                        <svg
                            class="manual-preview-fullscreen__icon manual-preview-fullscreen__icon--collapse"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M8 3v3a2 2 0 0 1-2 2H3"></path>
                            <path d="M16 3v3a2 2 0 0 0 2 2h3"></path>
                            <path d="M8 21v-3a2 2 0 0 0-2-2H3"></path>
                            <path d="M16 21v-3a2 2 0 0 1 2-2h3"></path>
                        </svg>
                    </button>

                    <iframe
                        id="manualPreview"
                        src="{{ asset('documentos/Introducción a Moodle.pdf') }}#page=1&zoom=page-width&toolbar=0&navpanes=0"
                        title="Vista previa del manual"
                    ></iframe>
                    {{-- =========================================================
                         REPRODUCTOR DEL VIDEO GENERAL
                        ========================================================= --}}

                        <video
                            id="manualVideo"
                            class="manual-preview-video"
                            controls
                            preload="metadata"
                            style="display: none;"
                        >
                            Tu navegador no soporta la reproducción de video.
                        </video>

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

<style>
    .manual-preview-card__viewer {
        position: relative;
    }

    .manual-preview-fullscreen {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 20;
        width: 38px;
        height: 38px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.96);
        color: #343747;
        cursor: pointer;
        box-shadow: 0 2px 7px rgba(0, 0, 0, 0.14);
        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .manual-preview-fullscreen:hover {
        background: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.18);
    }

    .manual-preview-fullscreen__icon {
        width: 20px;
        height: 20px;
    }

    .manual-preview-fullscreen__icon--collapse {
        display: none;
    }

    .manual-preview-card__viewer:fullscreen {
        width: 100%;
        height: 100%;
        background: #ffffff;
        border-radius: 0;
    }

    .manual-preview-card__viewer:fullscreen iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .manual-preview-card__viewer:fullscreen .manual-preview-fullscreen {
        top: 14px;
        right: 14px;
    }

    .manual-preview-card__viewer:fullscreen .manual-preview-fullscreen__icon--expand {
        display: none;
    }

    .manual-preview-card__viewer:fullscreen .manual-preview-fullscreen__icon--collapse {
        display: block;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const manualPreviewViewer =
            document.getElementById('manualPreviewViewer');

        const manualPreviewFullscreen =
            document.getElementById('manualPreviewFullscreen');

        if (!manualPreviewViewer || !manualPreviewFullscreen) {
            return;
        }

        manualPreviewFullscreen.addEventListener('click', async function () {

            try {

                if (document.fullscreenElement === manualPreviewViewer) {
                    await document.exitFullscreen();
                    return;
                }

                if (!document.fullscreenElement) {
                    await manualPreviewViewer.requestFullscreen();
                }

            } catch (error) {

                console.error(
                    'No fue posible cambiar la vista a pantalla completa:',
                    error
                );

            }

        });

        document.addEventListener('fullscreenchange', function () {

            const isFullscreen =
                document.fullscreenElement === manualPreviewViewer;

            manualPreviewFullscreen.setAttribute(
                'aria-label',
                isFullscreen
                    ? 'Salir de pantalla completa'
                    : 'Ver manual en pantalla completa'
            );

            manualPreviewFullscreen.setAttribute(
                'title',
                isFullscreen
                    ? 'Salir de pantalla completa'
                    : 'Pantalla completa'
            );

        });

    });
</script>

@endsection