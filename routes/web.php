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

    return view('evidencias.descargar');

})->name('evidencias.descargar');

Route::get('/evidencias/descarga', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    return view('evidencias.descarga');

})->name('evidencias.descarga');
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
| PROCESO DE ANÁLISIS
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/analizando', function () {

    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }

    return view('evidencias.analizando');

})->name('evidencias.analizando');

/*
|--------------------------------------------------------------------------
| DESCARGAR REPORTE PDF DE PRUEBA
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/reporte-prueba', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    /*
     * PDF que utilizaremos temporalmente como prueba.
     */
    $archivoOrigen = public_path(
        'documentos/diagrama_proceso.pdf'
    );

    /*
     * Carpeta donde se guardará el reporte.
     */
    $carpetaDestino = storage_path(
        'app/reportes'
    );

    /*
     * Nombre y ruta final.
     */
    $archivoDestino = $carpetaDestino
        . DIRECTORY_SEPARATOR
        . 'Reporte_Analisis_Prueba.pdf';


    /*
     * Verificar que exista el PDF de prueba.
     */
    if (!file_exists($archivoOrigen)) {

        abort(
            404,
            'No se encontró el PDF de prueba.'
        );

    }


    /*
     * Crear carpeta de reportes si no existe.
     */
    if (!is_dir($carpetaDestino)) {

        mkdir(
            $carpetaDestino,
            0755,
            true
        );

    }


    /*
     * Copiar PDF de prueba.
     */
    copy(
        $archivoOrigen,
        $archivoDestino
    );


    /*
     * Descargarlo.
     */
    return response()->file(
    $archivoDestino,
    [
        'Content-Type' => 'application/pdf'
    ]
);

})->name('evidencias.reporte.prueba');

/*
|--------------------------------------------------------------------------
| RESULTADOS DEL ANÁLISIS
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/resultados', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    return redirect()->route('evidencias.reporte.prueba');

})->name('evidencias.resultados');
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

/*
|--------------------------------------------------------------------------
| ANALIZAR EVIDENCIAS
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/analizar', function () {

    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }

    return view('evidencias.analizar');

})->name('evidencias.analizar');