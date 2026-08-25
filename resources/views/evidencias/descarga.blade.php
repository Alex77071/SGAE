@ -0,0 +1,239 @@
@extends('layouts.app')

@section('title', 'SGAE - Descargar evidencias')

@section('content')

<section class="download-process-page">

    <div class="download-process-card">

        {{-- =====================================================
             ESTADO: DESCARGANDO
        ====================================================== --}}
        <div
            class="download-process-state"
            id="downloadProgressState"
        >

            <div class="download-process-loader">

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

            </div>

            <h2 class="download-process-title">
                Descargando evidencias...
            </h2>

            <p class="download-process-description">
                Espere mientras se descargan las evidencias seleccionadas.
            </p>


            {{-- BARRA DE PROGRESO --}}
            <div class="download-progress">

                <div class="download-progress__track">

                    <div
                        class="download-progress__bar"
                        id="downloadProgressBar"
                    ></div>

                </div>

                <span
                    class="download-progress__percentage"
                    id="downloadProgressPercentage"
                >
                    0%
                </span>

            </div>

        </div>


        {{-- =====================================================
             ESTADO: DESCARGA COMPLETADA
        ====================================================== --}}
        <div
            class="download-process-state download-process-state--complete"
            id="downloadCompleteState"
            hidden
        >

            {{-- ICONO CHECK --}}
            <div class="download-complete-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    ></circle>

                    <path
                        d="m7.5 12 3 3 6-6"
                    ></path>
                </svg>

            </div>


            <h2 class="download-process-title">
                ¡Descarga completada!
            </h2>

            <p class="download-process-description">
                La descarga de las evidencias se realizó correctamente
            </p>


            {{-- RESUMEN --}}
            <div class="download-summary">

                <div class="download-summary__item download-summary__item--exam">

                    <strong>
                        Examen final
                    </strong>

                    <span>
                        Programación | Grupo A
                    </span>

                </div>


                <div class="download-summary__divider"></div>


                <div class="download-summary__item">

                    <strong>
                        32
                    </strong>

                    <span>
                        carpetas de<br>
                        alumnos
                    </span>

                </div>


                <div class="download-summary__divider"></div>


                <div class="download-summary__item">

                    <strong>
                        1,248
                    </strong>

                    <span>
                        Imágenes
                    </span>

                </div>

            </div>


            {{-- ACCIONES --}}
            <div class="download-complete-actions">

                <button
                    type="button"
                    class="download-complete-button download-complete-button--primary"
                >

                    <span class="download-complete-button__icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M12 2v4"></path>
                            <path d="M12 18v4"></path>
                            <path d="m4.93 4.93 2.83 2.83"></path>
                            <path d="m16.24 16.24 2.83 2.83"></path>
                            <path d="M2 12h4"></path>
                            <path d="M18 12h4"></path>
                            <path d="m4.93 19.07 2.83-2.83"></path>
                            <path d="m16.24 7.76 2.83-2.83"></path>
                        </svg>

                    </span>

                    <span>
                        Analizar evidencia
                    </span>

                </button>


                <a
                    href="{{ route('inicio') }}"
                    class="download-complete-button download-complete-button--secondary"
                >

                    <span class="download-complete-button__icon">

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

        </div>

    </div>

</section>

@endsection