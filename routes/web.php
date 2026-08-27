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

Route::get('/recursos/manuales', function () {

    /*
     * Solo usuarios autenticados con Moodle pueden acceder.
     */
    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    return view('recursos.manuales');

})->name('manuales');
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

Route::get('/evidencias/analizar', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    return view('evidencias.analizar');

})->name('evidencias.analizar');
/*
|--------------------------------------------------------------------------
| DESCARGAR REPORTE PDF DE PRUEBA
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/reporte-prueba', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

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

    return view('evidencias.resultados');

})->name('evidencias.resultados');

/*
|--------------------------------------------------------------------------
| HISTORIAL DE ANÁLISIS
|--------------------------------------------------------------------------
*/

Route::get('/evidencias/historial', function () {

    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }


    /*
     * DATOS TEMPORALES
     *
     * Después estos datos vendrán de la base de datos.
     */
    $analisis = [

        [
            'id' => 1,
            'nombre' => 'Examen final',
            'fecha' => '13/05/2026',
            'imagenes' => 248,
            'grupo' => 'Grupo A',
        ],

        [
            'id' => 2,
            'nombre' => 'Práctica 3',
            'fecha' => '12/05/2026',
            'imagenes' => 193,
            'grupo' => 'Grupo A',
        ],

    ];


    return view(
        'evidencias.historial',
        compact('analisis')
    );

})->name('evidencias.historial');


/*
|--------------------------------------------------------------------------
| VISUALIZAR PDF DEL HISTORIAL
|--------------------------------------------------------------------------
*/

Route::get(
    '/evidencias/historial/reporte/{id}',
    function ($id) {

        if (!session('moodle_authenticated')) {
            return redirect()->route('login');
        }


        /*
         * PDFs DE EJEMPLO
         */
        $reportes = [

            1 => public_path(
                'documentos/reportes/examen_final.pdf'
            ),

            2 => public_path(
                'documentos/reportes/practica_3.pdf'
            ),

        ];


        /*
         * Validar que exista un reporte
         * asociado al ID seleccionado.
         */
        if (!isset($reportes[$id])) {

            abort(
                404,
                'No se encontró el análisis seleccionado.'
            );

        }


        $archivo = $reportes[$id];


        /*
         * Validar que el PDF exista físicamente.
         */
        if (!file_exists($archivo)) {

            abort(
                404,
                'No se encontró el archivo PDF.'
            );

        }


        /*
         * Mostrar el PDF directamente
         * en el navegador.
         */
        return response()->file(
            $archivo,
            [
                'Content-Type' => 'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="' .
                    basename($archivo) .
                    '"',
            ]
        );

    }
)->name('evidencias.historial.reporte');
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

    return view('recursos.manuales');

})->name('manuales');


/*
|--------------------------------------------------------------------------
| PREGUNTAS FRECUENTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/ayuda/preguntas-frecuentes',
    function () {

        if (!session('moodle_authenticated')) {
            return redirect()->route('login');
        }

        return view('ayuda.preguntas');

    }
)->name('preguntas.frecuentes');


/*
|--------------------------------------------------------------------------
| CONTACTO Y SOPORTE
|--------------------------------------------------------------------------
*/

Route::get(
    '/ayuda/contacto',
    function () {

        if (!session('moodle_authenticated')) {
            return redirect()->route('login');
        }

        return view('ayuda.contacto');

    }
)->name('contacto.soporte');


/*
|--------------------------------------------------------------------------
| CERRAR SESIÓN
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [MoodleAuthController::class, 'logout']
)->name('logout');