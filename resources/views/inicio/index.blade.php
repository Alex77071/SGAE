@extends('layouts.app')

@section('title', 'SGAE - Inicio')


@section('content')

<section class="dashboard">


    {{-- =====================================================
         BIENVENIDA
    ====================================================== --}}

    <div class="dashboard-welcome">

        <div class="dashboard-welcome__line"></div>

        <div>

            <h2 class="dashboard-welcome__title">

                ¡Bienvenido, {{ session('moodle_fullname') }}!

            </h2>

            <p class="dashboard-welcome__text">

                Desde aquí puedes descargar, analizar y consultar
                las evidencias de los exámenes realizados.

            </p>

        </div>

    </div>


    {{-- =====================================================
         EVIDENCIAS
    ====================================================== --}}

    <section class="dashboard-card evidence-card">

        <div class="dashboard-card__header">

            <span class="dashboard-card__icon">

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


            <div>

                <h3 class="dashboard-card__title">
                    Evidencias
                </h3>

                <p class="dashboard-card__description">

                    Descarga y analiza las evidencias de los<br>
                    exámenes de Moodle.

                </p>

            </div>

        </div>


        <div class="evidence-steps">


            {{-- PASO 1 --}}

<article class="evidence-step">

    <span class="evidence-step__number">
        1
    </span>


    <div class="evidence-step__content">

        <a
            href="{{ route('evidencias.descargar') }}"
            class="outline-action"
        >

            <span class="outline-action__icon">

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

            Descargar carpetas

        </a>


        <p>

            Obtenga las carpetas con las imágenes
            descargadas

        </p>

    </div>

</article>


            {{-- PASO 2 --}}

<article class="evidence-step">

    <span class="evidence-step__number">
        2
    </span>


    <div class="evidence-step__content">

        <a
            href="#"
    id="openAnalysisContactModal"
    class="outline-action"
        >

            <span class="outline-action__icon" aria-hidden="true">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                >
                    <path d="M12 2v3"></path>
                    <path d="M12 19v3"></path>

                    <path d="M4.93 4.93l2.12 2.12"></path>
                    <path d="M16.95 16.95l2.12 2.12"></path>

                    <path d="M2 12h3"></path>
                    <path d="M19 12h3"></path>

                    <path d="M4.93 19.07l2.12-2.12"></path>
                    <path d="M16.95 7.05l2.12-2.12"></path>
                </svg>

            </span>

            Analizar carpetas

</a>


        <p>

            Ejecute el análisis con Inteligencia Artificial y
            consulte los resultados

        </p>

    </div>

</article>


            {{-- PASO 3 --}}

            <article class="evidence-step evidence-step--last">

                <span class="evidence-step__number">
                    3
                </span>


                <div class="evidence-step__content">

                   <button
    type="button"
    class="outline-action"
    id="openHistoryUnavailableModal"
>
    <span class="outline-action__icon" aria-hidden="true">

        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.7"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M4 20L15 9"></path>

            <path d="M13.5 7.5l3 3"></path>

            <path d="M7 3v4"></path>
            <path d="M5 5h4"></path>

            <path d="M18 3v4"></path>
            <path d="M16 5h4"></path>

            <path d="M19 13v4"></path>
            <path d="M17 15h4"></path>
        </svg>

    </span>

    Historial de análisis
</button>


                    <p>

                        Consulta el historial de evidencias procesadas.

                    </p>

                </div>

            </article>

        </div>

    </section>


    {{-- =====================================================
         PARTE INFERIOR
    ====================================================== --}}

    <div class="dashboard-bottom">


        {{-- ESTADO DEL SISTEMA --}}

        <section class="dashboard-card status-card">

            <div class="dashboard-card__header dashboard-card__header--compact">

                <span class="dashboard-card__icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                    >

                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="13"
                            rx="1"
                        ></rect>

                        <path
                            d="M8 21h8"
                        ></path>

                        <path
                            d="M12 17v4"
                        ></path>

                    </svg>

                </span>


                <div>

                    <h3 class="dashboard-card__title">
                        Estado del sistema
                    </h3>

                    <p class="dashboard-card__description">

                        Resumen del estado actual del sistema

                    </p>

                </div>

            </div>


            <div class="status-summary">

             <strong>
    {{ $carpetasPendientes }}
    {{ $carpetasPendientes === 1 ? 'carpeta pendiente' : 'carpetas pendientes' }}
    de un total de
    {{ $totalCarpetas }}
</strong>

<span>
    @if ($carpetasPendientes === 1)
        Lista para ser analizada
    @elseif ($carpetasPendientes > 1)
        Listas para ser analizadas
    @else
        No hay carpetas pendientes por analizar
    @endif
</span>

            </div>

        </section>


        {{-- RECURSOS --}}

        <section class="dashboard-card resources-card">

            <div class="dashboard-card__header dashboard-card__header--compact">

                <span class="dashboard-card__icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <path
                            d="M2 5.5A3.5 3.5 0 0 1 5.5 2H11v17H5.5A3.5 3.5 0 0 0 2 22Z"
                        ></path>

                        <path
                            d="M22 5.5A3.5 3.5 0 0 0 18.5 2H13v17h5.5A3.5 3.5 0 0 1 22 22Z"
                        ></path>

                    </svg>

                </span>


                <div>

                    <h3 class="dashboard-card__title">
                        Recursos
                    </h3>

                    <p class="dashboard-card__description">

                        Consulta las guías y manuales de Moodle y del<br>
                        Sistema de Descarga de Evidencias.

                    </p>

                </div>

            </div>


<div class="resources-actions">

    <a
        href="{{ route('recursos.diagrama') }}"
        class="outline-action resources-action resources-action--link"
    >

        <span
            class="outline-action__icon"
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

                <rect
                    x="3"
                    y="4"
                    width="18"
                    height="16"
                    rx="2"
                ></rect>

                <path
                    d="M9.5 9a2.5 2.5 0 1 1 3.8 2.1c-.8.5-1.3 1-1.3 2"
                ></path>

                <path d="M12 16h.01"></path>

            </svg>

        </span>

        Conoce el flujo del proceso

        </a>


        <a
            href="{{ route('manuales') }}"
            class="outline-action resources-action"
        >

            <span class="outline-action__icon" aria-hidden="true">

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

            Manuales de usuario

        </a>
            </div>

        </section>


    </div>

</section>

{{-- =====================================================
     MODAL - CONTACTAR AL ENCARGADO
===================================================== --}}

<div
    class="logout-modal"
    id="analysisContactModal"
    aria-hidden="true"
>

    <div
        class="logout-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="analysisContactModalTitle"
    >

        <div class="logout-modal__icon" aria-hidden="true">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="12" cy="8" r="4"></circle>
                <path d="M4 21v-2a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v2"></path>
                <path d="M18 8h3"></path>
                <path d="M19.5 6.5v3"></path>
            </svg>

        </div>

        <h2
            class="logout-modal__title"
            id="analysisContactModalTitle"
        >
            Contacta al encargado
        </h2>

        <p
            style="
                margin: -15px 0 30px;
                max-width: 390px;
                text-align: center;
                font-size: 17px;
                line-height: 1.5;
                color: #555555;
            "
        >
            Coordinador I. C. Carlos Alberto Martínez Sandoval
        </p>

        <div class="logout-modal__actions">

            <button
                type="button"
                id="closeAnalysisContactModal"
                class="logout-modal__button logout-modal__button--cancel"
            >
                Cancelar
            </button>

            <button
                type="button"
                id="confirmAnalysisContactModal"
                class="logout-modal__button logout-modal__button--confirm"
            >
                Entendido
            </button>

        </div>

    </div>

</div>

{{-- =====================================================
     MODAL - HISTORIAL NO DISPONIBLE
===================================================== --}}

<div
    class="logout-modal"
    id="historyUnavailableModal"
    aria-hidden="true"
>

    <div
        class="logout-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="historyUnavailableModalTitle"
    >

        <div class="logout-modal__icon" aria-hidden="true">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M12 8v5"></path>
                <path d="M12 17h.01"></path>
            </svg>

        </div>


        <h2
            class="logout-modal__title"
            id="historyUnavailableModalTitle"
        >
            Funcionalidad no disponible
        </h2>


        <p
            style="
                margin: -15px 0 30px;
                max-width: 390px;
                text-align: center;
                font-size: 17px;
                line-height: 1.5;
                color: #555555;
            "
        >
            Esta funcionalidad no se encuentra disponible actualmente.
        </p>


        <div
    class="logout-modal__actions"
    style="
        display: flex;
        justify-content: center;
        align-items: center;
    "
>

    <button
        type="button"
        id="closeHistoryUnavailableModal"
        class="logout-modal__button logout-modal__button--confirm"
    >
        Entendido
    </button>

</div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const openButton =
        document.getElementById('openAnalysisContactModal');

    const modal =
        document.getElementById('analysisContactModal');

    const closeButton =
        document.getElementById('closeAnalysisContactModal');

    const confirmButton =
        document.getElementById('confirmAnalysisContactModal');


    if (!openButton || !modal) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | ABRIR MODAL
    |--------------------------------------------------------------------------
    */

    openButton.addEventListener('click', function () {

        modal.classList.add('logout-modal--open');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | CERRAR CON CANCELAR
    |--------------------------------------------------------------------------
    */

    if (closeButton) {

        closeButton.addEventListener('click', function () {

            modal.classList.remove('logout-modal--open');

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR CON ENTENDIDO
    |--------------------------------------------------------------------------
    */

    if (confirmButton) {

        confirmButton.addEventListener('click', function () {

            modal.classList.remove('logout-modal--open');

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

        });

    }

    /*
|--------------------------------------------------------------------------
| MODAL - HISTORIAL NO DISPONIBLE
|--------------------------------------------------------------------------
*/

const openHistoryUnavailableModal =
    document.getElementById(
        'openHistoryUnavailableModal'
    );

const historyUnavailableModal =
    document.getElementById(
        'historyUnavailableModal'
    );

const closeHistoryUnavailableModal =
    document.getElementById(
        'closeHistoryUnavailableModal'
    );


if (
    openHistoryUnavailableModal &&
    historyUnavailableModal
) {

    openHistoryUnavailableModal.addEventListener(
        'click',
        function () {

            historyUnavailableModal.classList.add(
                'logout-modal--open'
            );

            historyUnavailableModal.setAttribute(
                'aria-hidden',
                'false'
            );

        }
    );

}


if (
    closeHistoryUnavailableModal &&
    historyUnavailableModal
) {

    closeHistoryUnavailableModal.addEventListener(
        'click',
        function () {

            historyUnavailableModal.classList.remove(
                'logout-modal--open'
            );

            historyUnavailableModal.setAttribute(
                'aria-hidden',
                'true'
            );

        }
    );

}

});
</script>

@endsection