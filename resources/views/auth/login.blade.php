@extends('layouts.guest')

@section('content')

<section class="login-screen">

    <div class="login-screen__grid">

        {{-- COLUMNA IZQUIERDA --}}
        <div class="login-screen__left">

            {{-- TÍTULO Y DESCRIPCIÓN --}}
            <div class="login-copy">

                <div class="login-copy__line"></div>

                <div class="login-copy__content">

                    <h2 class="login-copy__title">
                        Inicia Sesión
                    </h2>

                    <p class="login-copy__text">
                        Accede al Sistema de Gestión y Análisis de
                        Evidencias para gestionar y analizar con IA las
                        evidencias de los exámenes en Moodle.
                    </p>

                </div>

            </div>


            {{-- FORMULARIO --}}
            <form
                class="login-form"
                action="{{ route('login.submit') }}"
                method="POST"
            >

                @csrf


                {{-- MENSAJE DE ERROR DE MOODLE --}}
                @if(session('login_error'))

                    <div
                        class="login-error"
                        style="
                            width: 100%;
                            padding: 12px 16px;
                            margin-bottom: 18px;
                            border: 1px solid #dba0a0;
                            border-radius: 8px;
                            background: #fceaea;
                            color: #823233;
                            font-size: 14px;
                            line-height: 1.4;
                        "
                    >
                        {{ session('login_error') }}
                    </div>

                @endif


                {{-- USUARIO --}}
                <div class="form-field">

                    <label
                        for="usuario"
                        class="form-label"
                    >
                        Usuario
                    </label>

                    <div class="input-group">

                        <span
                            class="input-group__icon"
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
                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>

                        <input
                            type="text"
                            id="usuario"
                            name="usuario"
                            class="form-input"
                            placeholder="Ingresa tu usuario"
                            value="{{ old('usuario') }}"
                            autocomplete="username"
                            required
                        >

                    </div>

                    @error('usuario')
                        <small style="color: #823233;">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                {{-- CONTRASEÑA --}}
                <div class="form-field">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Contraseña
                    </label>

                    <div class="input-group input-group--password">

                        <span
                            class="input-group__icon"
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
                                    x="4"
                                    y="11"
                                    width="16"
                                    height="9"
                                    rx="2"
                                ></rect>

                                <path d="M8 11V8a4 4 0 1 1 8 0v3"></path>
                            </svg>
                        </span>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required
                        >


                        {{-- BOTÓN MOSTRAR / OCULTAR --}}
                        <button
                            type="button"
                            class="input-group__action"
                            id="togglePassword"
                            aria-label="Mostrar contraseña"
                        >

                            {{-- OJO ABIERTO --}}
                            <svg
                                id="eyeOpen"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>


                            {{-- OJO TACHADO --}}
                            <svg
                                id="eyeClosed"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                style="display: none;"
                            >
                                <path d="M3 3l18 18"></path>

                                <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>

                                <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a16.8 16.8 0 0 1-2.1 3.2"></path>

                                <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8a10.3 10.3 0 0 0 4.1-.8"></path>
                            </svg>

                        </button>

                    </div>


                    @error('password')
                        <small style="color: #823233;">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                {{-- INICIAR SESIÓN --}}
                <button
                    type="submit"
                    class="action-btn action-btn--primary"
                >

                    <span
                        class="action-btn__icon"
                        aria-hidden="true"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M10 17l5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M21 21V3"></path>
                        </svg>
                    </span>

                    <span>
                        Iniciar sesión
                    </span>

                </button>


                {{-- REGISTRO --}}
                <a
                    href="https://cv.utm.mx/"
                    class="action-btn action-btn--secondary"
                >

                    <span
                        class="action-btn__icon"
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
                            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>

                            <circle
                                cx="9.5"
                                cy="7"
                                r="3.5"
                            ></circle>

                            <path d="M19 8v6"></path>

                            <path d="M16 11h6"></path>
                        </svg>
                    </span>

                    <span>
                        Registrarme
                    </span>

                </a>


                {{-- OLVIDÉ MI CONTRASEÑA --}}
                <a
                    href="#"
                    class="forgot-link"
                >
                    ¿Olvidaste tu contraseña?
                </a>

            </form>

        </div>


        {{-- COLUMNA DERECHA --}}
        <div class="login-screen__right">

            <div class="steps-card">

                {{-- TÍTULO PRINCIPAL --}}
                <p class="steps-card__title">
                    Gestiona las evidencias de tus exámenes en tres simples pasos.
                </p>


                {{-- IMAGEN CON LOS 3 ICONOS --}}
                <div class="steps-card__media">

                    <img
                        src="{{ asset('images/logos/inicio2.png') }}"
                        alt="Proceso para gestionar evidencias"
                        class="steps-card__image"
                    >

                </div>


                {{-- TEXTOS DE CADA PASO --}}
                <div class="steps-card__labels">

                    <p class="steps-card__label">
                        Descarga evidencias
                    </p>

                    <p class="steps-card__label">
                        Analizar con IA
                    </p>

                    <p class="steps-card__label">
                        Descargar resultados
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const password =
        document.getElementById('password');

    const togglePassword =
        document.getElementById('togglePassword');

    const eyeOpen =
        document.getElementById('eyeOpen');

    const eyeClosed =
        document.getElementById('eyeClosed');


    if (
        !password ||
        !togglePassword ||
        !eyeOpen ||
        !eyeClosed
    ) {
        return;
    }


    togglePassword.addEventListener('click', function () {

        if (password.type === 'password') {

            // Mostrar contraseña
            password.type = 'text';

            // Cambiar icono
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';

            togglePassword.setAttribute(
                'aria-label',
                'Ocultar contraseña'
            );

        } else {

            // Ocultar contraseña
            password.type = 'password';

            // Cambiar icono
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';

            togglePassword.setAttribute(
                'aria-label',
                'Mostrar contraseña'
            );

        }

    });

});

</script>

@endsection