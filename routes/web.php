<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::post('/login', function (Request $request) {

    $request->validate([

        'usuario' => 'required|string|max:100',
        'password' => 'required|string',

    ]);


    /*
     * Por ahora guardamos el usuario escrito.
     *
     * Más adelante este dato vendrá de Moodle.
     */
    session([

        'usuario' => $request->usuario

    ]);


    return redirect()->route('inicio');

})->name('login.submit');


/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/

Route::get('/inicio', function () {

    if (!session()->has('usuario')) {

        return redirect()->route('login');

    }


    return view('inicio.index');

})->name('inicio');


/*
|--------------------------------------------------------------------------
| CERRAR SESIÓN
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {

    /*
     * Eliminamos todos los datos de la sesión.
     */
    $request->session()->flush();


    /*
     * Regeneramos el token de sesión.
     */
    $request->session()->regenerateToken();


    /*
     * Regresamos al login.
     */
    return redirect()->route('login');

})->name('logout');