<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGAE - Iniciar sesión</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="guest-body">
    <div class="guest-page">
        @include('components.header')

        <main class="guest-main">
            @yield('content')
        </main>

        @include('components.footer')
    </div>
</body>
</html>