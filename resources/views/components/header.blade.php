<header class="main-header">
    <div class="header-left">
        <img src="{{ asset('images/logos/logo1.png') }}" alt="Logo 1" class="logo">
        <img src="{{ asset('images/logos/logo2.png') }}" alt="Logo 2" class="logo">
        <h1>Sistema de Gestión y Análisis de Evidencias</h1>
    </div>

    <div class="header-right">
        @isset($usuario)
            <span>{{ $usuario }}</span>
        @endisset
    </div>
</header>