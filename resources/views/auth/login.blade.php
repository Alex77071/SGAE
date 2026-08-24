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
                action="#"
                method="POST"
                onsubmit="return false;"
            >

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
                            autocomplete="username"
                        >

                    </div>

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

                                <path
                                    d="M8 11V8a4 4 0 1 1 8 0v3"
                                ></path>
                            </svg>
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="input-group__action"
                            aria-label="Mostrar contraseña"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                                ></path>

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="3"
                                ></circle>

                                <path
                                    d="M4 20 20 4"
                                ></path>
                            </svg>
                        </button>

                    </div>

                </div>


                {{-- BOTÓN INICIAR SESIÓN --}}
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

                    <span>Iniciar sesión</span>

                </button>


                {{-- BOTÓN REGISTRO --}}
                <button
                    type="button"
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
                            <path
                                d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"
                            ></path>

                            <circle
                                cx="9.5"
                                cy="7"
                                r="3.5"
                            ></circle>

                            <path d="M19 8v6"></path>
                            <path d="M16 11h6"></path>
                        </svg>
                    </span>

                    <span>Registrarme</span>

                </button>


                {{-- RECUPERAR CONTRASEÑA --}}
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

                <p class="steps-card__title">
                    Gestiona las evidencias de tus exámenes en tres simples pasos.
                </p>

                <div class="steps-card__media">

                    <img
                        src="{{ asset('images/logos/inicio2.png') }}"
                        alt="Proceso para gestionar evidencias"
                        class="steps-card__image"
                    >

                </div>

            </div>

        </div>

    </div>

</section>

@endsection