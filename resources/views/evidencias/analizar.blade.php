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
         1. FILTRAR
    ====================================================== --}}

    <section class="download-filter-card analyze-filter-card">

        <h3 class="download-section-title">
            1. Filtrar
        </h3>


        <div class="download-filter-grid">

            {{-- CURSO --}}
            <div class="download-filter-field">

                <label
                    for="analyzeCurso"
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
                        id="analyzeCurso"
                        name="curso"
                        class="download-select__control"
                    >

                        <option value="">
                            Todos los cursos
                        </option>

                        <option value="programacion">
                            Programación
                        </option>

                        <option value="bases-datos">
                            Bases de datos
                        </option>

                        <option value="redes">
                            Redes
                        </option>

                    </select>

                </div>

            </div>


            {{-- GRUPO --}}
            <div class="download-filter-field">

                <label
                    for="analyzeGrupo"
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

                            <path
                                d="M3 19v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"
                            ></path>

                            <circle cx="17" cy="9" r="2"></circle>

                            <path
                                d="M17 13h1a3 3 0 0 1 3 3v2"
                            ></path>

                        </svg>

                    </span>

                    <select
                        id="analyzeGrupo"
                        name="grupo"
                        class="download-select__control"
                    >

                        <option value="">
                            Todos los grupos
                        </option>

                        <option value="a">
                            Grupo A
                        </option>

                        <option value="b">
                            Grupo B
                        </option>

                        <option value="c">
                            Grupo C
                        </option>

                    </select>

                </div>

            </div>


            {{-- EXAMEN --}}
            <div class="download-filter-field">

                <label
                    for="analyzeExamen"
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
                        id="analyzeExamen"
                        name="examen"
                        class="download-select__control"
                    >

                        <option value="">
                            Todos los exámenes
                        </option>

                        <option value="final">
                            Examen Final
                        </option>

                        <option value="parcial-1">
                            Primer parcial
                        </option>

                        <option value="parcial-2">
                            Segundo parcial
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         2. RESULTADOS
    ====================================================== --}}

    <section class="analyze-results">

        <h3 class="download-section-title">
            2. Resultados
        </h3>


        <div class="analyze-table-wrapper">

            <table class="analyze-table">

                <thead>

                    <tr>

                        <th>Examen</th>

                        <th>Grupo</th>

                        <th>Curso</th>

                        <th>Alumnos</th>

                        <th>Imágenes</th>

                        <th>Estado</th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>
                            Examen Final Programación
                        </td>

                        <td>
                            Grupo A
                        </td>

                        <td>
                            Programación
                        </td>

                        <td>
                            32/33
                        </td>

                        <td>
                            1,234
                        </td>

                        <td>
                            Pendiente
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </section>


  {{-- =====================================================
     BOTÓN ANALIZAR
====================================================== --}}

<div class="analyze-evidence-action">

    <a
        href="{{ route('evidencias.analizando') }}"
        class="analyze-evidence-button"
    >

        <span
            class="analyze-evidence-button__icon"
            aria-hidden="true"
        >
            ▶
        </span>

        <span>
            Analizar
        </span>

    </a>

</div>

</section>

@endsection