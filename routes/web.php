<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodleAuthController;
use App\Http\Controllers\EvidenciasController;


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {

    /*
     * Verificar si el usuario ya leyó
     * el Aviso de Privacidad.
     */
    $privacyAccepted =
        $request->cookie('sgae_privacy_accepted');


    /*
     * Si todavía no lo ha aceptado,
     * mostrar primero el Aviso de Privacidad.
     */
    if ($privacyAccepted !== '1.0') {

        return redirect()->route(
            'legal.privacidad'
        );

    }


    /*
     * Si ya lo aceptó,
     * mostrar normalmente el login.
     */
    return view('auth.login');

})->name('login');

/*
|--------------------------------------------------------------------------
| AVISO DE PRIVACIDAD
|--------------------------------------------------------------------------
*/

/*
 * Mostrar Aviso de Privacidad.
 */
Route::get('/privacidad', function (Request $request) {

    /*
     * Si ya fue aceptado, no es necesario
     * volver a mostrarlo.
     */
    if (
        $request->cookie(
            'sgae_privacy_accepted'
        ) === '1.0'
    ) {

        return redirect()->route('login');

    }


    return view('legal.privacidad');

})->name('legal.privacidad');


/*
 * Aceptar Aviso de Privacidad.
 */
Route::post(
    '/privacidad/aceptar',
    function (Request $request) {

        /*
         * Validar que marque la casilla.
         */
        $request->validate([
            'privacy_accepted' => [
                'required',
                'accepted',
            ],
        ]);


        /*
         * Guardar cookie durante un año.
         *
         * 525600 minutos = 365 días.
         */
        return redirect()
            ->route('login')
            ->withCookie(
                cookie(
                    'sgae_privacy_accepted',
                    '1.0',
                    525600
                )
            );

    }
)->name('legal.privacidad.aceptar');


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
| TÉRMINOS Y CONDICIONES
|--------------------------------------------------------------------------
*/

Route::get('/terminos', function (Request $request) {

    /*
     * Solo puede entrar un profesor
     * que ya inició sesión con Moodle.
     */
    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }


    $userId = session('moodle_user_id');


    if (!$userId) {

        return redirect()->route('login');

    }


    /*
     * Cookie específica para este profesor.
     */
    $cookieName =
        'sgae_terms_' .
        substr(
            hash(
                'sha256',
                (string) $userId
            ),
            0,
            16
        );


    /*
     * Si ya aceptó la versión actual,
     * enviarlo directamente a inicio.
     */
    if (
        $request->cookie($cookieName) === '1.0'
    ) {

        return redirect()->route('inicio');

    }


    return view('legal.terminos');

})->name('legal.terminos');


Route::post(
    '/terminos/aceptar',
    function (Request $request) {

        /*
         * Debe tener sesión Moodle.
         */
        if (!session('moodle_authenticated')) {

            return redirect()->route('login');

        }


        /*
         * Debe marcar la casilla.
         */
        $request->validate([

            'terms_accepted' => [
                'required',
                'accepted',
            ],

        ]);


        $userId = session('moodle_user_id');


        if (!$userId) {

            return redirect()->route('login');

        }


        /*
         * Crear cookie específica
         * para el profesor.
         */
        $cookieName =
            'sgae_terms_' .
            substr(
                hash(
                    'sha256',
                    (string) $userId
                ),
                0,
                16
            );


        /*
         * Guardar aceptación durante un año.
         */
        return redirect()
            ->route('inicio')
            ->withCookie(
                cookie(
                    $cookieName,
                    '1.0',
                    525600
                )
            );

    }
)->name('legal.terminos.aceptar');


/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/

Route::get('/inicio', function (Request $request) {

    /*
     * Verificar autenticación Moodle.
     */
    if (!session('moodle_authenticated')) {

        return redirect()->route('login');

    }


    /*
     * Obtener profesor actual.
     */
    $userId = session('moodle_user_id');


    if (!$userId) {

        return redirect()->route('login');

    }


    /*
     * Verificar aceptación
     * de Términos y Condiciones.
     */
    $termsCookieName =
        'sgae_terms_' .
        substr(
            hash(
                'sha256',
                (string) $userId
            ),
            0,
            16
        );


    if (
        $request->cookie(
            $termsCookieName
        ) !== '1.0'
    ) {

        return redirect()->route(
            'legal.terminos'
        );

    }


    return view('inicio.index');

})->name('inicio');

/*
|--------------------------------------------------------------------------
| DESCARGAR EVIDENCIAS
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| PANTALLA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get(
    '/evidencias/descargar',
    [EvidenciasController::class, 'index']
)->name('evidencias.descargar');


/*
|--------------------------------------------------------------------------
| OBTENER CURSOS DEL PROFESOR
|--------------------------------------------------------------------------
*/

Route::get(
    '/evidencias/cursos',
    [EvidenciasController::class, 'cursos']
)->name('evidencias.cursos');


/*
|--------------------------------------------------------------------------
| OBTENER GRUPOS DEL CURSO
|--------------------------------------------------------------------------
*/

Route::get(
    '/evidencias/grupos',
    [EvidenciasController::class, 'grupos']
)->name('evidencias.grupos');


/*
|--------------------------------------------------------------------------
| OBTENER EXÁMENES DEL CURSO
|--------------------------------------------------------------------------
*/

Route::get(
    '/evidencias/examenes',
    [EvidenciasController::class, 'examenes']
)->name('evidencias.examenes');


/*
|--------------------------------------------------------------------------
| EJECUTAR DESCARGA
|--------------------------------------------------------------------------
*/

Route::post(
    '/evidencias/descargar',
    [EvidenciasController::class, 'descargar']
)->name('evidencias.descargar.ejecutar');


/*
|--------------------------------------------------------------------------
| PANTALLA DE PROGRESO DE DESCARGA
|--------------------------------------------------------------------------
*/

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


Route::get('/debug-sesion', function () {

    return response()->json([

        'moodle_authenticated' =>
            session('moodle_authenticated') === true,

        'tiene_user_id' =>
            !empty(session('moodle_user_id')),

        'tiene_token' =>
            !empty(session('moodle_token')),

    ]);

});

Route::get(
    '/evidencias/datos-examen',
    [EvidenciasController::class, 'datosExamen']
)->name('evidencias.datos-examen');

/*
|--------------------------------------------------------------------------
| PROBAR CAPTURAS DE PROCTORING
|--------------------------------------------------------------------------
*/

Route::get(
    '/evidencias/probar-capturas',
    [EvidenciasController::class, 'probarCapturas']
)->name('evidencias.probar-capturas');