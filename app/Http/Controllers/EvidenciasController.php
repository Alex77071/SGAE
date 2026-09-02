<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use App\Services\MoodleService;
use Illuminate\Support\Facades\Http;

class EvidenciasController extends Controller
{
        private $moodleService;


    public function __construct(
        MoodleService $moodleService
    ) {
        $this->moodleService =
            $moodleService;
    }
    /*
    |--------------------------------------------------------------------------
    | EJECUTAR SCRIPT DE PYTHON
    |--------------------------------------------------------------------------
    */
private function ejecutarPython(array $argumentos): array
{
    $script = base_path(
        'python/evidencias_web.py'
    );

    $process = new Process(
        array_merge(
            [
                'python',
                $script,
            ],
            $argumentos
        )
    );


    /*
    |--------------------------------------------------------------------------
    | VARIABLES PARA PYTHON
    |--------------------------------------------------------------------------
    */

    $process->setEnv([

        'MOODLE_DB_HOST' =>
            env('MOODLE_DB_HOST', 'localhost'),

        'MOODLE_DB_PORT' =>
            env('MOODLE_DB_PORT', '3306'),

        'MOODLE_DB_USER' =>
            env('MOODLE_DB_USER'),

        'MOODLE_DB_PASSWORD' =>
            env('MOODLE_DB_PASSWORD'),

        /*
         * IMPORTANTE:
         * descargar_evidencias.py utiliza MOODLE_DB_NAME.
         */
        'MOODLE_DB_NAME' =>
            env(
                'MOODLE_DB_NAME',
                env('MOODLE_DB_DATABASE', 'moodle')
            ),

        'MOODLEDATA_FILEDIR' =>
            env(
                'MOODLEDATA_FILEDIR',
                '/var/moodledata/filedir'
            ),

        /*
         * Aquí Python dejará temporalmente
         * el ZIP para que Laravel lo descargue.
         */
        'MOODLE_OUTPUT_DIR' =>
            env(
                'MOODLE_OUTPUT_DIR',
                storage_path('app/evidencias')
            ),

    ]);


    /*
    |--------------------------------------------------------------------------
    | TIEMPO MÁXIMO
    |--------------------------------------------------------------------------
    */

    $process->setTimeout(300);

    $process->run();


    /*
    |--------------------------------------------------------------------------
    | ERROR EJECUTANDO PYTHON
    |--------------------------------------------------------------------------
    */

    if (!$process->isSuccessful()) {

        return [
            'ok' => false,
            'message' =>
                trim($process->getErrorOutput())
                ?: 'Error ejecutando Python.',
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | LEER JSON
    |--------------------------------------------------------------------------
    */

    $salida = trim(
        $process->getOutput()
    );


    $data = json_decode(
        $salida,
        true
    );


    if (!is_array($data)) {

        return [
            'ok' => false,
            'message' =>
                'Python devolvió una respuesta inválida.',
        ];

    }


    return $data;
}

    /*
    |--------------------------------------------------------------------------
    | PANTALLA PRINCIPAL DE DESCARGA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        if (!session('moodle_authenticated')) {

            return redirect()
                ->route('login');
        }

        return view(
            'evidencias.descargar'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER CURSOS
    |--------------------------------------------------------------------------
    */
    public function cursos()
    {
        if (!session('moodle_authenticated')) {

            return response()->json(
                [
                    'ok' => false,
                    'message' => 'Sesión no válida.',
                ],
                401
            );

        }


        $token =
            session('moodle_token');

        $userId =
            (int) session('moodle_user_id');


        if (!$token || !$userId) {

            return response()->json([
                'ok' => false,
                'message' =>
                    'No se encontró la sesión de Moodle.',
            ]);

        }


        $resultado =
            $this->moodleService
                ->getTeacherCourses(
                    $token,
                    $userId
                );


        if (!$resultado['success']) {

            return response()->json([
                'ok' => false,
                'message' =>
                    $resultado['message']
                    ?? 'No fue posible obtener los cursos.',
            ]);

        }


        return response()->json([
            'ok' => true,

            'cursos' =>
                $resultado['data'] ?? [],
        ]);
    }
    

        private function profesorTieneCurso(
        string $token,
        int $userId,
        int $courseId
    ): bool {

        $resultado =
            $this->moodleService
                ->getTeacherCourses(
                    $token,
                    $userId
                );


        if (!$resultado['success']) {
            return false;
        }


        $cursos =
            $resultado['data']
            ?? [];


        foreach ($cursos as $curso) {

            if (
                (int) ($curso['id'] ?? 0)
                ===
                $courseId
            ) {

                return true;

            }

        }


        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | OBTENER GRUPOS
    |--------------------------------------------------------------------------
    */
    public function grupos(Request $request)
    {
        if (!session('moodle_authenticated')) {

            return response()->json(
                [
                    'ok' => false,
                    'message' => 'Sesión no válida.',
                ],
                401
            );

        }


        $request->validate([
            'courseid' =>
                'required|integer',
        ]);


        $token =
            session('moodle_token');

        $userId =
            (int) session('moodle_user_id');

        $courseId =
            (int) $request->courseid;


        if (!$token || !$userId) {

            return response()->json([
                'ok' => false,
                'message' =>
                    'No se encontró la sesión de Moodle.',
            ]);

        }


        if (
            !$this->profesorTieneCurso(
                $token,
                $userId,
                $courseId
            )
        ) {

            return response()->json([
                'ok' => false,
                'message' =>
                    'No tienes acceso a este curso.',
            ]);

        }


        $resultado =
            $this->moodleService
                ->getCourseGroups(
                    $token,
                    $courseId
                );


        if (!$resultado['success']) {

            return response()->json([
                'ok' => false,
                'message' =>
                    $resultado['message']
                    ?? 'No fue posible obtener los grupos.',
            ]);

        }


        return response()->json([
            'ok' => true,

            'grupos' =>
                $resultado['data'] ?? [],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER EXÁMENES
    |--------------------------------------------------------------------------
    */
    public function examenes(Request $request)
    {
        if (!session('moodle_authenticated')) {

            return response()->json(
                [
                    'ok' => false,
                    'message' => 'Sesión no válida.',
                ],
                401
            );

        }


        $request->validate([
            'courseid' =>
                'required|integer',
        ]);


        $token =
            session('moodle_token');

        $userId =
            (int) session('moodle_user_id');

        $courseId =
            (int) $request->courseid;


        if (!$token || !$userId) {

            return response()->json([
                'ok' => false,
                'message' =>
                    'No se encontró la sesión de Moodle.',
            ]);

        }


        if (
            !$this->profesorTieneCurso(
                $token,
                $userId,
                $courseId
            )
        ) {

            return response()->json([
                'ok' => false,
                'message' =>
                    'No tienes acceso a este curso.',
            ]);

        }


        $resultado =
            $this->moodleService
                ->getCourseQuizzes(
                    $token,
                    $courseId
                );


        if (!$resultado['success']) {

            return response()->json([
                'ok' => false,
                'message' =>
                    $resultado['message']
                    ?? 'No fue posible obtener los exámenes.',
            ]);

        }


        return response()->json([
            'ok' => true,

            'examenes' =>
                $resultado['data'] ?? [],
        ]);
    }


    public function datosExamen(Request $request)
{
    if (!session('moodle_authenticated')) {

        return response()->json(
            [
                'ok' => false,
                'message' => 'Sesión no válida.',
            ],
            401
        );
    }


    $request->validate([
        'courseid' =>
            'required|integer',

        'quizid' =>
            'required|integer',
    ]);


    $token =
        session('moodle_token');


    $userId =
        (int) session('moodle_user_id');


    $courseId =
        (int) $request->courseid;


    $quizId =
        (int) $request->quizid;


    if (!$token || !$userId) {

        return response()->json([
            'ok' => false,
            'message' =>
                'No se encontró la sesión de Moodle.',
        ]);
    }


    if (
        !$this->profesorTieneCurso(
            $token,
            $userId,
            $courseId
        )
    ) {

        return response()->json([
            'ok' => false,
            'message' =>
                'No tienes acceso a este curso.',
        ]);
    }


    $resultado =
        $this->moodleService
            ->getQuizStudents(
                $token,
                $courseId,
                $quizId
            );


    if (!$resultado['success']) {

        return response()->json([
            'ok' => false,
            'message' =>
                $resultado['message']
                ?? 'No fue posible obtener los alumnos.',
        ]);
    }


    return response()->json([
        'ok' => true,

        'alumnos_total' =>
            $resultado['data']['alumnos_total']
            ?? 0,

        'alumnos_con_intento' =>
            $resultado['data']['alumnos_con_intento']
            ?? 0,
    ]);
}


    /*
    |--------------------------------------------------------------------------
    | DESCARGAR EVIDENCIAS
    |--------------------------------------------------------------------------
    */



   public function descargar(Request $request)
{
    if (!session('moodle_authenticated')) {

        return redirect()
            ->route('login');

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR FILTROS
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'courseid' =>
            'required|integer',

        'quizid' =>
            'required|integer',

        'groupid' =>
            'nullable|integer',

    ]);


    /*
    |--------------------------------------------------------------------------
    | PROFESOR AUTENTICADO
    |--------------------------------------------------------------------------
    */

    $userId =
        session('moodle_user_id');


    if (!$userId) {

        return redirect()
            ->route('login');

    }


    /*
    |--------------------------------------------------------------------------
    | EJECUTAR PYTHON
    |--------------------------------------------------------------------------
    |
    | Python recibirá:
    |
    | descargar USERID COURSEID QUIZID GROUPID
    |
    */

    $resultado =
        $this->ejecutarPython([

            'descargar',

            (string) $userId,

            (string) $request->courseid,

            (string) $request->quizid,

            $request->groupid
                ? (string) $request->groupid
                : 'null',

        ]);


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    if (
        !isset($resultado['ok'])
        ||
        !$resultado['ok']
    ) {

        return back()->with(
            'error',
            $resultado['message']
                ?? 'No fue posible descargar las evidencias.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ZIP GENERADO
    |--------------------------------------------------------------------------
    */

    $zip =
        $resultado['zip']
        ?? null;


    if (
        !$zip
        ||
        !file_exists($zip)
    ) {

        return back()->with(
            'error',
            'No se encontró el archivo ZIP generado.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ENTREGAR ZIP AL NAVEGADOR
    |--------------------------------------------------------------------------
    */

    return response()
        ->download(
            $zip,
            basename($zip)
        )
        ->deleteFileAfterSend(true);
}

public function iniciarAnalisis(Request $request)
{
    if (!session('moodle_authenticated')) {
        return redirect()->route('login');
    }

    $request->validate([
        'archivo' => 'required|file|mimes:zip',
    ]);

    $archivo = $request->file('archivo');

    $stream = fopen(
        $archivo->getRealPath(),
        'r'
    );

    try {

        $respuesta = Http::timeout(60)
            ->attach(
                'archivo',
                $stream,
                $archivo->getClientOriginalName()
            )
            ->post(
                'http://127.0.0.1:8000/analizar/iniciar'
            );

    } catch (\Throwable $error) {

        return back()->withErrors([
            'archivo' =>
                'No fue posible conectar con la API de análisis: '
                . $error->getMessage()
        ]);

    } finally {

        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    if (!$respuesta->successful()) {

        return back()->withErrors([
            'archivo' =>
                'La API no pudo iniciar el análisis.'
        ]);
    }

    $datos = $respuesta->json();

    if (
        !isset($datos['job_id'])
        ||
        !isset($datos['archivo'])
    ) {

        return back()->withErrors([
            'archivo' =>
                'La API devolvió una respuesta inválida.'
        ]);
    }

    session([
        'analisis_job_id' =>
            $datos['job_id'],

        'analisis_archivo' =>
            $datos['archivo'],
    ]);

    return redirect()->route(
        'evidencias.analizando'
    );
}
public function progresoAnalisis()
{
    $jobId = session(
        'analisis_job_id'
    );

    if (!$jobId) {

        return response()->json(
            [
                'estado' => 'error',
                'mensaje' =>
                    'No existe un análisis activo.',
            ],
            404
        );
    }

    try {

        $respuesta = Http::timeout(15)
            ->get(
                'http://127.0.0.1:8000'
                . '/analizar/progreso/'
                . $jobId
            );

    } catch (\Throwable $error) {

        return response()->json(
            [
                'estado' => 'error',
                'mensaje' =>
                    'No fue posible conectar con la API.',
            ],
            500
        );
    }

    if (!$respuesta->successful()) {

        return response()->json(
            [
                'estado' => 'error',
                'mensaje' =>
                    'No fue posible consultar el progreso.',
            ],
            500
        );
    }

    $progreso = $respuesta->json();

    if (
        isset($progreso['estado'])
        &&
        $progreso['estado'] === 'completado'
    ) {

        try {

            $respuestaResultado =
                Http::timeout(15)
                    ->get(
                        'http://127.0.0.1:8000'
                        . '/analizar/resultado/'
                        . $jobId
                    );

            if (
                $respuestaResultado
                    ->successful()
            ) {

                session([
                    'analisis_resultado' =>
                        $respuestaResultado
                            ->json()
                ]);
            }

        } catch (\Throwable $error) {
            // El progreso ya terminó.
            // El resultado podrá consultarse después.
        }
    }

    return response()->json(
        $progreso
    );
}
public function reporteActual(Request $request)
{
    $jobId = session(
        'analisis_job_id'
    );

    if (!$jobId) {

        abort(
            404,
            'No existe un reporte disponible.'
        );
    }

    try {

        $respuesta = Http::timeout(60)
            ->get(
                'http://127.0.0.1:8000'
                . '/analizar/reporte/'
                . $jobId
            );

    } catch (\Throwable $error) {

        abort(
            500,
            'No fue posible conectar con la API.'
        );
    }

    if (!$respuesta->successful()) {

        abort(
            404,
            'No fue posible obtener el reporte.'
        );
    }

    $resultado = session(
        'analisis_resultado',
        []
    );

    $nombrePdf =
        $resultado['reporte']['nombre']
        ?? 'Reporte_Analisis.pdf';

    $descargar =
        $request->query('download') == '1';

    $disposicion =
        $descargar
            ? 'attachment'
            : 'inline';

    return response(
        $respuesta->body(),
        200,
        [
            'Content-Type' =>
                'application/pdf',

            'Content-Disposition' =>
                $disposicion
                . '; filename="'
                . $nombrePdf
                . '"',
        ]
    );
}
}