@extends('layouts.app')

@section('title', 'SGAE - Descargar evidencias')

@section('content')

<section class="download-evidence-page">

    {{-- =====================================================
         MIGAS DE PAN
    ====================================================== --}}

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
            Descargar evidencias
        </span>
    </nav>


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


                <tbody>

                    <tr>

                        <td class="download-table__exam">
                            Examen Final P...
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
                            <span class="download-table__date">
                                12/05/2026
                            </span>

                            <span class="download-table__time">
                                10 a. m.
                            </span>
                        </td>

                        <td>

                            <button
                                type="button"
                                class="download-details-button"
                            >

                                <span
                                    class="download-details-button__icon"
                                    aria-hidden="true"
                                >
                                    •••
                                </span>

                                <span>
                                    Detalles
                                </span>

                            </button>

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

                <span>
                    Descargar seleccionados
                </span>

            </button>

        </div>

    </section>

</section>

@endsection