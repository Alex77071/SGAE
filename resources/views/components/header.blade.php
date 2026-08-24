@php
    $showUser = $showUser ?? false;
    $usuario = $usuario ?? 'Carlos';
@endphp

<header class="site-header">
    <div class="site-header__container">

        <div class="site-header__branding">

            <img
                src="{{ asset('images/logos/utm_logo3.png') }}"
                alt="Universidad Tecnológica de la Mixteca"
                class="site-header__logo site-header__logo--utm"
            >

            <div class="site-header__divider"></div>

            <img
                src="{{ asset('images/logos/usalab_logo2.png') }}"
                alt="UsaLab"
                class="site-header__logo site-header__logo--usalab"
            >

        </div>

        <h1 class="site-header__title">
            Sistema de Gestión y Análisis de Evidencias
        </h1>

        @if($showUser)

            <div class="site-header__profile">

                <span class="profile-icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21a8 8 0 0 1 16 0"></path>
                    </svg>
                </span>

                <span class="profile-name">
                    {{ $usuario }}
                </span>

                <button
                    type="button"
                    class="profile-arrow"
                    aria-label="Abrir menú de usuario"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>

            </div>

        @else

            <div class="site-header__spacer"></div>

        @endif

    </div>
</header>