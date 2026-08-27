@extends('layouts.app')

@section('title', 'SGAE - Analizar evidencias')

@section('content')

<section class="analyze-evidence-page">
    <nav
        class="download-evidence-breadcrumb"
        aria-label="Navegación"
    >

        <a
            href="{{ route('inicio') }}"
            class="download-evidence-breadcrumb__link"
        >
            Inicio
        </a>

        <span
            class="download-evidence-breadcrumb__separator"
            aria-hidden="true"
        >
            ›
        </span>

        <span class="download-evidence-breadcrumb__current">
            Analizar evidencias
        </span>

    </nav>


    {{-- =====================================================
         TÍTULO
    ====================================================== --}}

    <div class="analyze-evidence-heading">

        <div class="analyze-evidence-heading__line"></div>

        <h2 class="analyze-evidence-heading__title">
            Analizar evidencias
        </h2>

    </div>


    {{-- =====================================================
     SELECCIONAR ARCHIVO
====================================================== --}}

<section class="analyze-file-card">

    <div
        class="analyze-file-icon"
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
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <path d="M14 2v6h6"></path>
            <path d="M8 13h8"></path>
            <path d="M8 17h5"></path>
        </svg>
    </div>


    <h3 class="analyze-file-title">
        Selecciona el archivo de evidencias
    </h3>


    <p class="analyze-file-description">
        Selecciona el archivo que contiene las evidencias
        que deseas analizar.
    </p>


    <input
        type="file"
        id="analysisFileInput"
        class="analyze-file-input"
        accept=".zip"
    >


    <button
        type="button"
        class="analyze-file-button"
        id="analysisFileButton"
    >

        <span
            class="analyze-file-button__icon"
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
                <path d="M3 7h5l2 2h11"></path>
                <path d="M5 7h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z"></path>
            </svg>
        </span>

        <span>
            Seleccionar archivo
        </span>

    </button>


    <p class="analyze-file-help">
        Formato permitido: archivo ZIP
    </p>

</section>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const fileInput =
        document.getElementById('analysisFileInput');

    const fileButton =
        document.getElementById('analysisFileButton');


    if (!fileInput || !fileButton) {
        return;
    }


    /*
     * Abrir administrador de archivos.
     */
    fileButton.addEventListener('click', function () {

        fileInput.click();

    });


    /*
     * Cuando el usuario seleccione un archivo,
     * pasar ficticiamente a la pantalla de análisis.
     */
    fileInput.addEventListener('change', function () {

        if (!fileInput.files.length) {
            return;
        }


        const selectedFile =
            fileInput.files[0];


        /*
         * Por ahora no se procesa ni se sube el archivo.
         *
         * Únicamente validamos que exista una selección
         * y avanzamos a la siguiente pantalla.
         */
        window.location.href =
            "{{ route('evidencias.analizando') }}";

    });

});

</script>

</section>

@endsection