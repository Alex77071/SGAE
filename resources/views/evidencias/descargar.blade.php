@extends('layouts.app')

@section('title', 'SGAE - Descargar evidencias')

@section('content')

<section
    class="download-evidence-page"
    id="downloadEvidencePage"
    data-courses-url="{{ route('evidencias.cursos') }}"
    data-groups-url="{{ route('evidencias.grupos') }}"
    data-exams-url="{{ route('evidencias.examenes') }}"
    data-exam-data-url="{{ route('evidencias.datos-examen') }}"
    data-captures-url="{{ route('evidencias.capturas') }}"
    data-download-url="{{ route('evidencias.descargar.ejecutar') }}"
    data-csrf-token="{{ csrf_token() }}"
    data-download-progress-url="{{ route('evidencias.descarga') }}"
>

    {{-- =====================================================
         TÍTULO
    ====================================================== --}}

    <div class="download-evidence-heading">

        <div class="download-evidence-heading__line"></div>

        <div>

            <h2 class="download-evidence-heading__title">
                Descargar evidencias
            </h2>

        </div>

    </div>


    {{-- =====================================================
         1. FILTRAR
    ====================================================== --}}

    <section class="download-filter-card">

        <h3 class="download-section-title">
            1. Filtrar
        </h3>


        <div class="download-filter-grid">


            {{-- CURSO --}}
            <div class="download-filter-field">

                <label
                    for="curso"
                    class="download-filter-label"
                >
                    Curso:
                </label>

                <div class="download-select">

                    <span
                        class="download-select__icon"
                        aria-hidden="true"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m3 9 9-4 9 4-9 4-9-4Z"></path>
                            <path d="M7 11v5c3 2 7 2 10 0v-5"></path>
                        </svg>
                    </span>

              <select
    id="curso"
    name="courseid"
    class="download-select__control"
>
<option value="" selected disabled>
    Cargando cursos...
</option>

</select>

                </div>

            </div>


            {{-- GRUPO --}}
            <div class="download-filter-field">

                <label
                    for="grupo"
                    class="download-filter-label"
                >
                    Grupo:
                </label>

                <div class="download-select">

                    <span
                        class="download-select__icon"
                        aria-hidden="true"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="9" cy="8" r="3"></circle>

                            <path d="M3 19v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path>

                            <circle cx="17" cy="9" r="2"></circle>

                            <path d="M17 13h1a3 3 0 0 1 3 3v2"></path>
                        </svg>
                    </span>

                  <select
    id="grupo"
    name="groupid"
    class="download-select__control"
    disabled
>
    <option value="">
        Selecciona primero un curso
    </option>
</select>

                </div>

            </div>


            {{-- EXAMEN --}}
            <div class="download-filter-field">

                <label
                    for="examen"
                    class="download-filter-label"
                >
                    Nombre del examen:
                </label>

                <div class="download-select">

                    <span
                        class="download-select__icon"
                        aria-hidden="true"
                    >
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

                            <path d="M7 8h10"></path>
                            <path d="M7 12h10"></path>
                            <path d="M7 16h7"></path>
                        </svg>
                    </span>
<select
    id="examen"
    name="quizid"
    class="download-select__control"
    disabled
>
    <option value="">
        Selecciona primero un curso
    </option>
</select>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         2. RESULTADOS
    ====================================================== --}}

    <section class="download-results">

        <h3 class="download-section-title">
            2. Resultados
        </h3>


        <div class="download-table-wrapper">

            <table class="download-table">

                <thead>

                    <tr>

                        <th>
                            Examen
                        </th>

                        <th>
                            Grupo
                        </th>

                        <th>
                            Curso
                        </th>

                        <th>
                            Alumnos
                        </th>

                        <th>
                            Imágenes
                        </th>

                        <th>
                            Fecha y hora del examen
                        </th>

                        <th aria-label="Acciones"></th>

                    </tr>

                </thead>


               <tbody id="downloadResultsBody">

    <tr>

        <td
            colspan="7"
            style="text-align: center;"
        >
            Selecciona un curso y un examen para mostrar resultados.
        </td>

    </tr>

</tbody>
            </table>

        </div>


      {{-- DESCARGAR SELECCIONADOS --}}

<div class="download-results__action">

    <button
        type="button"
        class="download-selected-button"
        id="downloadSelectedButton"
    >

        <span
            class="download-selected-button__icon"
            aria-hidden="true"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.9"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M12 3v12"></path>
                <path d="m7 10 5 5 5-5"></path>
                <path d="M5 20h14"></path>
            </svg>
        </span>

        <span id="downloadSelectedButtonText">
            Descargar seleccionados
        </span>

    </button>

</div>
</section>


{{-- =====================================================
     MODAL - DETALLES DE EVIDENCIAS
====================================================== --}}

<div
    class="evidence-gallery-modal"
    id="evidenceGalleryModal"
    aria-hidden="true"
    hidden
>

    <div
        class="evidence-gallery-modal__backdrop"
        data-close-evidence-modal
    ></div>


    <div
        class="evidence-gallery-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="evidenceGalleryTitle"
    >

        {{-- =============================================
             ENCABEZADO
        ============================================== --}}

        <div class="evidence-gallery-modal__header">

            <div class="evidence-gallery-modal__heading">

                <span
                    class="evidence-gallery-modal__icon"
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
                            y="3"
                            width="18"
                            height="18"
                            rx="2"
                        ></rect>

                        <circle
                            cx="8.5"
                            cy="8.5"
                            r="1.5"
                        ></circle>

                        <path
                            d="m21 15-5-5L5 21"
                        ></path>
                    </svg>
                </span>


                <div>

                    <h3
                        class="evidence-gallery-modal__title"
                        id="evidenceGalleryTitle"
                    >
                        Evidencias
                    </h3>


                    <p
                        class="evidence-gallery-modal__count"
                        id="evidenceGalleryCount"
                    >
                        Cargando imágenes...
                    </p>

                </div>

            </div>


            <button
                type="button"
                class="evidence-gallery-modal__close"
                id="evidenceGalleryClose"
                aria-label="Cerrar detalles"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                >
                    <path d="M6 6l12 12"></path>
                    <path d="M18 6 6 18"></path>
                </svg>
            </button>

        </div>


                {{-- =====================================================
             VISOR DE IMAGEN AMPLIADA
        ====================================================== --}}

        <div
            class="evidence-image-viewer"
            id="evidenceImageViewer"
            aria-hidden="true"
            hidden
        >

            {{-- FONDO OSCURO --}}
            <div
                class="evidence-image-viewer__backdrop"
                data-close-evidence-viewer
            ></div>


            {{-- CONTENIDO DEL VISOR --}}
            <div
                class="evidence-image-viewer__content"
                role="dialog"
                aria-modal="true"
                aria-label="Vista ampliada de evidencia"
            >

                {{-- CERRAR --}}
                <button
                    type="button"
                    class="evidence-image-viewer__close"
                    id="evidenceImageViewerClose"
                    aria-label="Cerrar imagen"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    >
                        <path d="M6 6l12 12"></path>
                        <path d="M18 6 6 18"></path>
                    </svg>
                </button>


                {{-- IMAGEN ANTERIOR --}}
                <button
                    type="button"
                    class="evidence-image-viewer__nav evidence-image-viewer__nav--prev"
                    id="evidenceImageViewerPrev"
                    aria-label="Imagen anterior"
                >
                    &lt;
                </button>


                {{-- IMAGEN AMPLIADA --}}
                <img
                    class="evidence-image-viewer__image"
                    id="evidenceImageViewerImage"
                    src=""
                    alt="Evidencia ampliada"
                >


                {{-- IMAGEN SIGUIENTE --}}
                <button
                    type="button"
                    class="evidence-image-viewer__nav evidence-image-viewer__nav--next"
                    id="evidenceImageViewerNext"
                    aria-label="Imagen siguiente"
                >
                    &gt;
                </button>


                {{-- INFORMACIÓN --}}
                <div
                    class="evidence-image-viewer__information"
                    id="evidenceImageViewerInformation"
                >
                </div>

            </div>

        </div>


        {{-- =============================================
             ESTADO DE CARGA
        ============================================== --}}

        <div
            class="evidence-gallery-modal__status"
            id="evidenceGalleryStatus"
        >
            Cargando evidencias...
        </div>


        {{-- =============================================
             GALERÍA
        ============================================== --}}

        <div
            class="evidence-gallery"
            id="evidenceGallery"
        >
        </div>


        {{-- =============================================
             CARGAR MÁS
        ============================================== --}}

        <div
            class="evidence-gallery-modal__more"
            id="evidenceGalleryMore"
            hidden
        >

            <button
                type="button"
                class="evidence-gallery-load-more"
                id="evidenceGalleryLoadMore"
            >
                <span>
                    Cargar más imágenes
                </span>

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="m7 10 5 5 5-5"></path>
                </svg>
            </button>

        </div>


        {{-- =============================================
             PIE DEL MODAL
        ============================================== --}}

        <div class="evidence-gallery-modal__footer">

            <span
                class="evidence-gallery-modal__shown"
                id="evidenceGalleryShown"
            >
            </span>


            <button
                type="button"
                class="evidence-gallery-modal__close-button"
                id="evidenceGalleryCloseButton"
            >
                Cerrar
            </button>

        </div>

    </div>

</div>


</section>

@endsection