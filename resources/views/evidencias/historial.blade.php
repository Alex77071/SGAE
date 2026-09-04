@extends('layouts.app')

@section('title', 'SGAE - Historial de análisis')

@section('content')

<section class="analysis-history-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="analysis-history-header">

        <div class="analysis-history-heading">

            <div
                class="analysis-history-heading__line"
                aria-hidden="true"
            ></div>

            <div>

                <h2 class="analysis-history-heading__title">
                    Historial de análisis realizados
                </h2>

                <p class="analysis-history-heading__description">
                    Consulte las carpetas que ya han sido procesadas
                </p>

            </div>

        </div>


        {{-- VOLVER AL INICIO --}}
        <a
            href="{{ route('inicio') }}"
            class="analysis-history-home"
        >

            <span
                class="analysis-history-home__icon"
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
                    <path d="M5.5 10.5V20h13v-9.5"></path>
                    <path d="M9.5 20v-6h5v6"></path>
                </svg>

            </span>

            <span>
                Volver al inicio
            </span>

        </a>

    </div>


    {{-- =====================================================
         TÍTULO DE SECCIÓN
    ====================================================== --}}

    <div class="analysis-history-section-heading">

        <h3>
            Análisis realizados
        </h3>

        <p>
            Aquí puede ver el historial de carpetas analizadas.
        </p>

    </div>


    {{-- =====================================================
         CONTENEDOR DE LA TABLA
    ====================================================== --}}

    <div class="analysis-history-card">

        <div class="analysis-history-table-wrapper">

            <table class="analysis-history-table">

                <thead>

                    <tr>

                        <th
                            class="analysis-history-table__radio"
                            aria-label="Seleccionar análisis"
                        ></th>

                        <th>
                            Nombre de la carpeta
                        </th>

                        <th>
                            Fecha de análisis
                        </th>

                        <th>
                            Imágenes
                        </th>

                        <th>
                            Carpetas contenidas
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($analisis as $item)

                        <tr
                            class="analysis-history-row"
                            data-history-row
                        >

                            {{-- RADIO BUTTON --}}
                            <td class="analysis-history-table__radio">

                                <input
                                    type="radio"
                                    name="analysisHistory"
                                    value="{{ $item['id'] }}"
                                    class="analysis-history-radio"

                                    data-report-url="{{ route(
                                        'evidencias.historial.reporte',
                                        $item['id']
                                    ) }}"

                                    aria-label="Seleccionar {{ $item['nombre'] }}"
                                >

                            </td>


                            {{-- NOMBRE --}}
                            <td>

                                <div class="analysis-history-folder">

                                    <span
                                        class="analysis-history-folder__icon"
                                        aria-hidden="true"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.6"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path
                                                d="M3 6.5A2.5 2.5 0 0 1 5.5 4H9l2 2h7.5A2.5 2.5 0 0 1 21 8.5v8A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5Z"
                                            ></path>
                                        </svg>

                                    </span>

                                    <span>
                                        {{ $item['nombre'] }}
                                    </span>

                                </div>

                            </td>


                            {{-- FECHA --}}
                            <td>
                                {{ $item['fecha'] }}
                            </td>


                            {{-- IMÁGENES --}}
                            <td>
                                {{ $item['imagenes'] }}
                            </td>


                            {{-- GRUPO --}}
                            <td>
                               {{ $item['carpetas'] }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         PARTE INFERIOR
    ====================================================== --}}

    <div class="analysis-history-bottom">


        {{-- CONTADOR --}}
        <p class="analysis-history-count">

            Mostrando 1 a {{ count($analisis) }}
            de {{ count($analisis) }} resultados

        </p>


        {{-- PAGINACIÓN --}}
        <div class="analysis-history-pagination">

            <button
                type="button"
                disabled
                aria-label="Página anterior"
            >
                &lt;
            </button>


            <button
                type="button"
                class="analysis-history-pagination__active"
            >
                1
            </button>


            <button
                type="button"
                disabled
                aria-label="Página siguiente"
            >
                &gt;
            </button>

        </div>


        {{-- VER REPORTE --}}
        <button
            type="button"
            class="analysis-history-view"
            id="historyViewButton"
            disabled
        >

            <span
                class="analysis-history-view__icon"
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

                    <path
                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                    ></path>

                    <circle
                        cx="12"
                        cy="12"
                        r="2.7"
                    ></circle>

                </svg>

            </span>

            <span>
                Ver reporte
            </span>

        </button>

    </div>

</section>

@endsection