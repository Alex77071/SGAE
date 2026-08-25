<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodleAuthController;


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('auth.login');

})->name('login');


/*
|--------------------------------------------------------------------------
| PROCESAR LOGIN
|--------------------------------------------------------------------------
*/

Route::post(
    '/login',
    [MoodleAuthController::class, 'login']
)->name('login.submit');


/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/

Route::get('/inicio', function () {

    /*
     * Verificamos que el usuario haya iniciado sesión
     * correctamente mediante Moodle.
     */

    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }

    return view('inicio.index');

})->name('inicio');


/*
|--------------------------------------------------------------------------
| DESCARGAR EVIDENCIAS
|--------------------------------------------------------------------------
|
| Esta ruta se agrega porque inicio/index.blade.php utiliza:
|
| route('evidencias.descargar')
|
| Por ahora devuelve una pantalla de prueba.
| Después aquí conectaremos la descarga real desde Moodle.
|
*/

Route::get('/evidencias/descargar', function () {

    /*
     * Solo usuarios autenticados con Moodle pueden acceder.
     */

    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }

    return 'Pantalla de descarga de evidencias';

})->name('evidencias.descargar');


/*
|--------------------------------------------------------------------------
| DIAGRAMA DEL PROCESO
|--------------------------------------------------------------------------
*/

Route::get('/recursos/diagrama', function () {

    /*
     * Esta pantalla también está protegida.
     */

    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }

    return view('recursos.diagrama');

})->name('recursos.diagrama');


/*
|--------------------------------------------------------------------------
| CERRAR SESIÓN
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [MoodleAuthController::class, 'logout']
)->name('logout');

/*
|--------------------------------------------------------------------------
| MANUALES DE USUARIO
|--------------------------------------------------------------------------
*/

Route::get('/recursos/manuales', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    return 'Pantalla de manuales de usuario';

})->name('manuales');