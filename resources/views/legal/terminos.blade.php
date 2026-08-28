@extends('layouts.guest')

@section('content')

<section class="terms-screen">

    <div class="terms-card">

        {{-- ENCABEZADO --}}
        <div class="terms-card__header">

            <h1 class="terms-card__title">
                Términos y Condiciones de Uso
            </h1>

            <p class="terms-card__subtitle">
                Sistema de Gestión y Análisis de Evidencias
            </p>

        </div>


        {{-- CONTENIDO --}}
        <div class="terms-card__content">

            <p>
                El Sistema de Gestión y Análisis de Evidencias (SGAE)
                es una herramienta de apoyo académico para la revisión
                y análisis de evidencias generadas durante evaluaciones
                en línea.
            </p>

            <p>
                Las imágenes y evidencias obtenidas mediante SGAE deberán
                utilizarse exclusivamente para fines académicos y de
                evaluación, y deberán ser consultadas y procesadas
                únicamente en los equipos de cómputo autorizados de
                la Universidad.
            </p>

            <p>
                No está permitido compartir, publicar, transferir o
                utilizar las imágenes para fines distintos a la evaluación
                correspondiente. Cualquier uso adicional, incluyendo
                investigación, publicaciones, presentaciones, capacitación,
                demostraciones u otros proyectos, deberá contar previamente
                con la autorización de UsaLab.
            </p>

            <p>
                El profesor deberá mantener la confidencialidad de las
                evidencias y de la información de los estudiantes.
                Los resultados generados mediante inteligencia artificial
                son únicamente una herramienta de apoyo y deberán ser
                revisados e interpretados por el profesor antes de tomar
                una decisión académica.
            </p>

            <p>
                Al continuar, el profesor declara haber leído y aceptado
                estos Términos y Condiciones de Uso.
            </p>

        </div>


        {{-- ACEPTACIÓN --}}
        <form
            action="{{ route('legal.terminos.aceptar') }}"
            method="POST"
            class="terms-form"
        >

            @csrf


            <label class="terms-acceptance">

                <input
                    type="checkbox"
                    name="terms_accepted"
                    id="termsAccepted"
                    value="1"
                    required
                >

                <span>
                    He leído y acepto los Términos y Condiciones.
                </span>

            </label>


            @error('terms_accepted')

                <p class="terms-error">
                    Debes aceptar los Términos y Condiciones
                    para continuar.
                </p>

            @enderror


            <button
                type="submit"
                class="terms-button"
                id="termsContinueButton"
                disabled
            >
                Aceptar y no volver a mostrar
            </button>

        </form>

    </div>

</section>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const checkbox =
        document.getElementById('termsAccepted');

    const button =
        document.getElementById('termsContinueButton');


    if (!checkbox || !button) {
        return;
    }


    checkbox.addEventListener('change', function () {

        button.disabled = !checkbox.checked;

    });

});

</script>

@endsection