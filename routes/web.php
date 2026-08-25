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
| DESCARGAR EVIDENCIAS
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/descargar', function () {

    if (!session()->has('usuario')) {
        return redirect()->route('login');
    }

    return view('evidencias.descargar');

})->name('evidencias.descargar');

/*
|--------------------------------------------------------------------------
| PROCESO DE DESCARGA DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/descarga', function () {

    if (!session()->has('usuario')) {
        return redirect()->route('login');
    }

    return view('evidencias.descarga');

})->name('evidencias.descarga');

/*
|--------------------------------------------------------------------------
| CERRAR SESIÓN
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [MoodleAuthController::class, 'logout']
)->name('logout');