@extends('layouts.app')

@section('title', 'SGAE - Contacto y soporte')

@section('content')

<section class="support-page">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}
    <div class="support-header">

        <div class="support-heading">
            <div class="support-heading__line"></div>

            <div class="support-heading__content">
                <h2 class="support-heading__title">
                    Contacto y soporte
                </h2>

                <p class="support-heading__description">
                    Estamos para ayudarle. Envíenos su consulta o reporte.
                </p>
            </div>
        </div>

        <a
            href="{{ route('inicio') }}"
            class="support-back-button"
        >
            <span
                class="support-back-button__icon"
                aria-hidden="true"
            >
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M3 11.5 12 4l9 7.5"></path>
                    <path d="M5.5 10.5V20h5v-6h3v6h5v-9.5"></path>
                </svg>
            </span>

            <span>Volver al inicio</span>
        </a>

    </div>

    {{-- =====================================================
         CONTENIDO
    ====================================================== --}}
    <div class="support-grid">

        {{-- INFORMACIÓN DE SOPORTE --}}
        <section class="support-card support-card--information">

            <h3 class="support-card__title">
                Información de soporte
            </h3>

            {{-- INSTITUTO --}}
            <div class="support-info-item">
                <div class="support-info-item__icon">
                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M3 9h18"></path>
                        <path d="M5 9v9"></path>
                        <path d="M9 9v9"></path>
                        <path d="M15 9v9"></path>
                        <path d="M19 9v9"></path>
                        <path d="M2 20h20"></path>
                        <path d="M12 3 3 7h18L12 3Z"></path>
                    </svg>
                </div>

                <div class="support-info-item__content">
                    <strong>Universidad Tecnológica de la Mixteca</strong>
                    <span>Instituto de Computación · Laboratorio de Usabilidad</span>
                </div>
            </div>

            {{-- CORREO --}}
            <div class="support-info-item">
                <div class="support-info-item__icon">
                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                        ></rect>
                        <path d="m3 7 9 6 9-6"></path>
                    </svg>
                </div>

                <div class="support-info-item__content">
                    <strong>Correo</strong>
                    <span>correo@utm.mx</span>
                </div>
            </div>

            {{-- HORARIO --}}
            <div class="support-info-item">
                <div class="support-info-item__icon">
                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        ></circle>
                        <path d="M12 7v5l4 2"></path>
                    </svg>
                </div>

                <div class="support-info-item__content">
                    <strong>Horario de atención</strong>
                    <span>Lunes a viernes</span>
                    <span>9:00 a 14:00 y de 16:00 a 19:00 h</span>
                </div>
            </div>

            {{-- TELÉFONO --}}
            <div class="support-info-item">
                <div class="support-info-item__icon">
                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path
                            d="M5.2 3.5 8 3l2.1 5-2 1.6a15.2 15.2 0 0 0 6.3 6.3l1.6-2 5 2.1-.5 2.8c-.2 1.1-1.2 1.9-2.3 1.8C10.1 20 4 13.9 3.4 5.8c-.1-1.1.7-2.1 1.8-2.3Z"
                        ></path>
                    </svg>
                </div>

                <div class="support-info-item__content">
                    <strong>Teléfono</strong>
                    <span>+52 953 532 0399 Ext. 123</span>
                </div>
            </div>

            {{-- DIRECCIÓN --}}
            <div class="support-info-item support-info-item--last">
                <div class="support-info-item__icon">
                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path
                            d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"
                        ></path>
                        <circle
                            cx="12"
                            cy="10"
                            r="2.5"
                        ></circle>
                    </svg>
                </div>

                <div class="support-info-item__content">
                    <strong>Dirección</strong>
                    <span>
                        Av. Doctor Modesto Seara Vázquez #1,
                        Acatlima, 69000
                    </span>
                    <span>
                        Heroica Cdad. de Huajuapan de León,
                        Oax. México
                    </span>
                </div>
            </div>

        </section>

        {{-- FORMULARIO --}}
        <section class="support-card support-card--form">

            <h3 class="support-card__title">
                Envíenos un mensaje
            </h3>

            <form
                id="supportMessageForm"
                class="support-form"
                novalidate
            >

                <div class="support-form__field">
                    <label
                        for="supportName"
                        class="support-form__label"
                    >
                        Nombre
                    </label>

                    <input
                        type="text"
                        id="supportName"
                        name="name"
                        class="support-form__input"
                        placeholder="Ingrese su nombre completo"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="support-form__field">
                    <label
                        for="supportEmail"
                        class="support-form__label"
                    >
                        Correo
                    </label>

                    <input
                        type="email"
                        id="supportEmail"
                        name="email"
                        class="support-form__input"
                        placeholder="Ingrese su dirección de correo"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="support-form__field">
                    <label
                        for="supportInstitution"
                        class="support-form__label"
                    >
                        Institución
                    </label>

                    <input
                        type="text"
                        id="supportInstitution"
                        name="institution"
                        class="support-form__input"
                        placeholder="Ingrese el nombre de su institución"
                        required
                    >
                </div>

                <div class="support-form__field support-form__field--message">
                    <label
                        for="supportMessage"
                        class="support-form__label"
                    >
                        Descripción del mensaje
                    </label>

                    <textarea
                        id="supportMessage"
                        name="message"
                        class="support-form__textarea"
                        placeholder="Escriba aquí su mensaje o consulta con el mayor detalle posible."
                        required
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="support-submit-button"
                    id="supportSubmitButton"
                    disabled
                >
                    <span
                        class="support-submit-button__icon"
                        aria-hidden="true"
                    >
                        <svg
                            width="23"
                            height="23"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                            <path d="M22 2 11 13"></path>
                        </svg>
                    </span>

                    <span>Enviar mensaje</span>
                </button>

            </form>

        </section>

    </div>

</section>

{{-- =====================================================
     ESTILOS
====================================================== --}}
<style>

/* ==========================================================
   PÁGINA
========================================================== */

.support-page {
    width: 100%;
    max-width: 1450px;

    height: 100%;
    min-height: 0;

    margin: 0 auto;

    padding:
        clamp(12px, 2vh, 22px)
        32px
        clamp(12px, 2vh, 22px);

    display: flex;
    flex-direction: column;

    overflow: hidden;

    box-sizing: border-box;
}


/* ==========================================================
   ENCABEZADO
========================================================== */

.support-header {
    width: 100%;

    margin-bottom:
        clamp(12px, 1.8vh, 20px);

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 30px;

    flex-shrink: 0;
}

.support-heading {
    display: flex;
    align-items: stretch;

    gap: 13px;
}

.support-heading__line {
    width: 4px;
    min-width: 4px;

    background: #823233;

    border-radius: 4px;
}

.support-heading__content {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.support-heading__title {
    margin: 0 0 5px;

    color: #823233;

    font-size:
        clamp(23px, 2vw, 28px);

    line-height: 1.1;

    font-weight: 700;
}

.support-heading__description {
    margin: 0;

    color: #343747;

    font-size:
        clamp(14px, 1.2vw, 16px);

    line-height: 1.25;
}


/* ==========================================================
   VOLVER AL INICIO
========================================================== */

.support-back-button {
    width: 335px;
    min-height: 44px;

    padding:
        7px
        20px;

    border:
        1px solid
        #9f4949;

    border-radius: 10px;

    background: transparent;

    color: #823233 !important;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 12px;

    flex-shrink: 0;

    font-family: inherit;

    font-size: 16px;
    font-weight: 500;

    text-decoration: none !important;

    box-sizing: border-box;

    transition:
        background-color 0.2s ease;
}

.support-back-button:hover {
    background:
        rgba(130, 50, 51, 0.06);
}

.support-back-button__icon {
    width: 24px;
    min-width: 24px;
    height: 24px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 24px;
}

.support-back-button__icon svg {
    width: 24px !important;
    height: 24px !important;

    display: block;
}


/* ==========================================================
   GRID
========================================================== */

.support-grid {
    width: 100%;

    flex: 1;
    min-height: 0;

    display: grid;

    grid-template-columns:
        minmax(330px, 0.9fr)
        minmax(480px, 1.2fr);

    gap: 24px;

    align-items: stretch;
}


/* ==========================================================
   TARJETAS
========================================================== */

.support-card {
    width: 100%;
    height: 100%;
    min-height: 0;

    padding:
        clamp(14px, 1.9vh, 21px)
        26px;

    border:
        1px solid
        #d7d4d4;

    border-radius: 20px;

    background: #ffffff;

    box-shadow:
        0
        3px
        10px
        rgba(0, 0, 0, 0.04);

    box-sizing: border-box;

    overflow: hidden;
}

.support-card__title {
    margin:
        0
        0
        clamp(10px, 1.4vh, 17px);

    color: #823233;

    font-size:
        clamp(18px, 1.6vw, 21px);

    line-height: 1.15;

    font-weight: 700;
}


/* ==========================================================
   INFORMACIÓN
========================================================== */

.support-card--information {
    display: flex;
    flex-direction: column;
}

.support-info-item {
    width: 100%;

    padding:
        clamp(5px, 0.9vh, 10px)
        0;

    display: flex;
    align-items: center;

    gap: 15px;

    flex: 1;

    border-bottom:
        1px solid
        #dedede;
}

.support-info-item--last {
    border-bottom: none;
}

.support-info-item__icon {
    width:
        clamp(40px, 5vh, 50px);

    min-width:
        clamp(40px, 5vh, 50px);

    height:
        clamp(40px, 5vh, 50px);

    padding:
        clamp(7px, 0.9vh, 10px);

    border-radius: 50%;

    background: #e6d3d2;

    color: #823233;

    display: flex;
    align-items: center;
    justify-content: center;

    box-sizing: border-box;
}

.support-info-item__icon svg {
    width:
        clamp(22px, 3vh, 28px)
        !important;

    height:
        clamp(22px, 3vh, 28px)
        !important;

    display: block;
}

.support-info-item__content {
    min-width: 0;

    display: flex;
    flex-direction: column;

    gap: 2px;

    color: #343747;

    font-size:
        clamp(13px, 1vw, 15px);

    line-height: 1.2;
}

.support-info-item__content strong {
    font-size:
        clamp(14px, 1.1vw, 16px);

    font-weight: 700;
}


/* ==========================================================
   FORMULARIO
========================================================== */

.support-card--form {
    display: flex;
    flex-direction: column;
}

.support-form {
    width: 100%;

    flex: 1;
    min-height: 0;

    display: flex;
    flex-direction: column;
}

.support-form__field {
    width: 100%;

    margin-bottom:
        clamp(6px, 1vh, 11px);
}

.support-form__field--message {
    flex: 1;
    min-height: 0;

    display: flex;
    flex-direction: column;
}

.support-form__label {
    display: block;

    margin-bottom: 5px;

    color: #343747;

    font-size:
        clamp(13px, 1vw, 15px);

    line-height: 1.15;

    font-weight: 600;
}

.support-form__input,
.support-form__textarea {
    width: 100%;

    border:
        1px solid
        #cfd0d4;

    border-radius: 9px;

    background: #ffffff;

    color: #343747;

    font-family: inherit;
    font-size: 14px;

    outline: none;

    box-sizing: border-box;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.support-form__input {
    height:
        clamp(38px, 5vh, 45px);

    padding:
        0
        14px;

    flex-shrink: 0;
}

.support-form__textarea {
    width: 100%;

    flex: 1;
    min-height: 72px;
    max-height: 130px;

    padding:
        10px
        14px;

    resize: none;
}

.support-form__input::placeholder,
.support-form__textarea::placeholder {
    color: #8c8e97;
}

.support-form__input:focus,
.support-form__textarea:focus {
    border-color: #823233;

    box-shadow:
        0 0 0 2px
        rgba(130, 50, 51, 0.08);
}


/* ==========================================================
   BOTÓN ENVIAR
========================================================== */

.support-submit-button {
    width: 65%;
    min-height:
        clamp(40px, 5.2vh, 47px);

    margin:
        auto
        0
        0
        auto;

    padding:
        7px
        22px;

    border:
        1px solid
        #823233;

    border-radius: 10px;

    background: #823233;

    color: #ffffff;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 11px;

    flex-shrink: 0;

    font-family: inherit;

    font-size:
        clamp(14px, 1.1vw, 16px);

    font-weight: 500;

    cursor: pointer;

    box-sizing: border-box;

    transition:
        background-color 0.2s ease,
        opacity 0.2s ease;
}

.support-submit-button__icon {
    width: 22px;
    min-width: 22px;
    height: 22px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.support-submit-button__icon svg {
    width: 22px !important;
    height: 22px !important;

    display: block;
}

.support-submit-button:not(:disabled):hover {
    background: #652728;
}

.support-submit-button:disabled {
    background: #c7b6b6;

    border-color: #c7b6b6;

    color: #f5f2f2;

    cursor: not-allowed;

    opacity: 0.72;
}


/* ==========================================================
   ESCRITORIO CON POCA ALTURA
========================================================== */

@media
    (min-width: 993px)
    and (max-height: 820px) {

    .support-page {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .support-header {
        margin-bottom: 10px;
    }

    .support-heading__title {
        font-size: 23px;
    }

    .support-heading__description {
        font-size: 13px;
    }

    .support-back-button {
        min-height: 40px;

        font-size: 15px;
    }

    .support-card {
        padding:
            12px
            20px;
    }

    .support-card__title {
        margin-bottom: 8px;

        font-size: 18px;
    }

    .support-info-item {
        padding:
            4px
            0;
    }

    .support-info-item__icon {
        width: 39px;
        min-width: 39px;
        height: 39px;

        padding: 7px;
    }

    .support-info-item__icon svg {
        width: 22px !important;
        height: 22px !important;
    }

    .support-info-item__content {
        font-size: 13px;
    }

    .support-info-item__content strong {
        font-size: 14px;
    }

    .support-form__field {
        margin-bottom: 5px;
    }

    .support-form__label {
        margin-bottom: 3px;

        font-size: 13px;
    }

    .support-form__input {
        height: 36px;

        font-size: 13px;
    }

    .support-form__textarea {
        min-height: 64px;

        font-size: 13px;
    }

    .support-submit-button {
        min-height: 39px;
    }

}


/* ==========================================================
   TABLET
========================================================== */

@media (max-width: 992px) {

    .support-page {
        height: auto;
        min-height: 100%;

        padding:
            22px
            24px
            32px;

        overflow-y: auto;
        overflow-x: hidden;
    }

    .support-header {
        flex-direction: column;
        align-items: flex-start;

        gap: 16px;
    }

    .support-back-button {
        width: 250px;
    }

    .support-grid {
        display: grid;

        grid-template-columns: 1fr;

        flex: none;

        gap: 20px;
    }

    .support-card {
        height: auto;

        overflow: visible;
    }

    .support-card--information {
        display: block;
    }

    .support-info-item {
        flex: none;

        padding:
            11px
            0;
    }

    .support-info-item__icon {
        width: 50px;
        min-width: 50px;
        height: 50px;
    }

    .support-form {
        height: auto;
        min-height: auto;
    }

    .support-form__field--message {
        display: block;
    }

    .support-form__input {
        height: 44px;
    }

    .support-form__textarea {
        min-height: 115px;
        max-height: none;

        resize: vertical;
    }

    .support-submit-button {
        margin-top: 8px;
    }

}


/* ==========================================================
   CELULAR
========================================================== */

@media (max-width: 576px) {

    .support-page {
        padding:
            18px
            14px
            28px;
    }

    .support-heading__title {
        font-size: 23px;
    }

    .support-heading__description {
        font-size: 14px;
    }

    .support-back-button {
        width: 100%;
    }

    .support-card {
        padding:
            18px
            15px;

        border-radius: 16px;
    }

    .support-info-item {
        align-items: flex-start;

        gap: 12px;
    }

    .support-info-item__icon {
        width: 45px;
        min-width: 45px;
        height: 45px;

        padding: 8px;
    }

    .support-info-item__content {
        font-size: 14px;
    }

    .support-info-item__content strong {
        font-size: 15px;
    }

    .support-form__label {
        font-size: 14px;
    }

    .support-submit-button {
        width: 100%;

        min-height: 45px;

        font-size: 15px;
    }

}

</style>


{{-- =====================================================
     VALIDACIÓN DEL FORMULARIO
====================================================== --}}
<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const form =
            document.getElementById(
                'supportMessageForm'
            );

        const submitButton =
            document.getElementById(
                'supportSubmitButton'
            );

        const name =
            document.getElementById(
                'supportName'
            );

        const email =
            document.getElementById(
                'supportEmail'
            );

        const institution =
            document.getElementById(
                'supportInstitution'
            );

        const message =
            document.getElementById(
                'supportMessage'
            );


        function checkForm() {

            const nameValid =
                name.value.trim() !== '';

            const emailValid =
                email.value.trim() !== ''
                &&
                email.checkValidity();

            const institutionValid =
                institution.value.trim() !== '';

            const messageValid =
                message.value.trim() !== '';

            submitButton.disabled =
                !(
                    nameValid
                    &&
                    emailValid
                    &&
                    institutionValid
                    &&
                    messageValid
                );

        }


        [
            name,
            email,
            institution,
            message
        ].forEach(
            function (field) {

                field.addEventListener(
                    'input',
                    checkForm
                );

            }
        );


        form.addEventListener(
            'submit',
            function (event) {

                event.preventDefault();

                if (
                    submitButton.disabled
                ) {
                    return;
                }

                alert(
                    'Tu mensaje está listo para enviarse.'
                );

            }
        );


        checkForm();

    }
);

</script>

@endsection
