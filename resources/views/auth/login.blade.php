@extends('layouts.guest')

@section('content')
<div class="login-container">
    <div class="login-box">
        <h2>Inicia Sesión</h2>
        <p>Accede al Sistema de Gestión y Análisis de Evidencias para gestionar y analizar con IA las evidencias de los exámenes en Moodle.</p>

        <form>
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" placeholder="Ingresa tu usuario">

            <label for="password">Contraseña</label>
            <input type="password" id="password" placeholder="Ingresa tu contraseña">

            <button type="submit">Iniciar sesión</button>
            <button type="button">Registrarme</button>

            <a href="#">¿Olvidaste tu contraseña?</a>
        </form>
    </div>

    <div class="info-box">
        <p>Gestiona las evidencias de tus exámenes en tres simples pasos.</p>
    </div>
</div>
@endsection