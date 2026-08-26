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

    {{-- FAVICON USALAB --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logos/usalab_logo2.png') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >

</head>

<body class="app-body">

    <div class="app-page">

        @include('components.header', [
            'showUser' => true,
            'usuario' => session('usuario')
        ])

        <main class="app-main">

            @yield('content')

        </main>

        @include('components.footer')

    </div>

    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>