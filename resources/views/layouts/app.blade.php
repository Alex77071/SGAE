<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'SGAE')
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >

</head>

<body class="app-body">

    <div class="app-page">

        @include('components.header', [
            'showUser' => true,
            'usuario' => $usuario ?? 'Carlos'
        ])

        <main class="app-main">
            @yield('content')
        </main>

        @include('components.footer')

    </div>

</body>

</html>