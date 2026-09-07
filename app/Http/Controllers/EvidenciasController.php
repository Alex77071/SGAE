<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use App\Services\MoodleService;
use Illuminate\Support\Facades\Http;
use App\Jobs\PrepararDescargaEvidencias;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use App\AnalisisHistorial;
use Illuminate\Support\Facades\Storage;

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

    /*
|--------------------------------------------------------------------------
| OBTENER CMID DEL EXAMEN
|--------------------------------------------------------------------------
*/

$examenes =
    $this->moodleService
        ->getCourseQuizzes(
            $token,
            $courseId
        );


if (!$examenes['success']) {

    return response()->json([
        'ok' => false,

        'message' =>
            'No fue posible obtener la información del examen.',
    ]);
}


$cmid = 0;


foreach (
    $examenes['data'] ?? []
    as $examen
) {

    if (
        (int) ($examen['id'] ?? 0)
        ===
        $quizId
    ) {

        $cmid =
            (int) ($examen['cmid'] ?? 0);

        break;
    }
}


if ($cmid <= 0) {

    return response()->json([
        'ok' => false,

        'message' =>
            'No se encontró el módulo del examen.',
    ]);
}


/*
|--------------------------------------------------------------------------
| CONTAR IMÁGENES
|--------------------------------------------------------------------------
*/

$alumnosIds =
    $resultado['data']['alumnos_ids']
    ?? [];


$imagenesResultado =
    $this->moodleService
        ->countProctoringImages(
            $token,
            $courseId,
            $cmid,
            $alumnosIds
        );


if (!$imagenesResultado['success']) {

    return response()->json([
        'ok' => false,

        'message' =>
            $imagenesResultado['message']
            ?? 'No fue posible contar las imágenes.',
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

    'imagenes' =>
        $imagenesResultado['data']['imagenes']
        ?? 0,
]);
}

/*
|--------------------------------------------------------------------------
| OBTENER CAPTURAS PARA EL MODAL
|--------------------------------------------------------------------------
*/

public function capturas(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDAR SESIÓN
    |--------------------------------------------------------------------------
    */

    if (!session('moodle_authenticated')) {

        return response()->json(
            [
                'ok' => false,
                'message' => 'Sesión no válida.',
            ],
            401
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR PARÁMETROS
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'courseid' =>
            'required|integer',

        'quizid' =>
            'required|integer',

        'offset' =>
            'nullable|integer|min:0',

        'limit' =>
            'nullable|integer|min:1|max:48',

    ]);


    /*
    |--------------------------------------------------------------------------
    | DATOS
    |--------------------------------------------------------------------------
    */

    $token =
        session('moodle_token');


    $profesorId =
        (int) session('moodle_user_id');


    $courseId =
        (int) $request->courseid;


    $quizId =
        (int) $request->quizid;


    $offset =
        max(
            0,
            (int) $request->input(
                'offset',
                0
            )
        );


    $limit =
        max(
            1,
            min(
                48,
                (int) $request->input(
                    'limit',
                    24
                )
            )
        );


    if (!$token || !$profesorId) {

        return response()->json([
            'ok' => false,

            'message' =>
                'No se encontró la sesión de Moodle.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR QUE EL PROFESOR TENGA ACCESO AL CURSO
    |--------------------------------------------------------------------------
    */

    if (
        !$this->profesorTieneCurso(
            $token,
            $profesorId,
            $courseId
        )
    ) {

        return response()->json([
            'ok' => false,

            'message' =>
                'No tienes acceso a este curso.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER CMID DEL EXAMEN
    |--------------------------------------------------------------------------
    */

    $examenes =
        $this->moodleService
            ->getCourseQuizzes(
                $token,
                $courseId
            );


    if (!$examenes['success']) {

        return response()->json([
            'ok' => false,

            'message' =>
                'No fue posible obtener la información del examen.',
        ]);
    }


    $cmid = 0;


    foreach (
        $examenes['data'] ?? []
        as $examen
    ) {

        if (
            (int) ($examen['id'] ?? 0)
            ===
            $quizId
        ) {

            $cmid =
                (int) (
                    $examen['cmid']
                    ?? 0
                );

            break;
        }
    }


    if ($cmid <= 0) {

        return response()->json([
            'ok' => false,

            'message' =>
                'No se encontró el módulo del examen.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER ALUMNOS QUE REALIZARON EL EXAMEN
    |--------------------------------------------------------------------------
    */

    $alumnos =
        $this->moodleService
            ->getQuizStudents(
                $token,
                $courseId,
                $quizId
            );


    if (!$alumnos['success']) {

        return response()->json([
            'ok' => false,

            'message' =>
                $alumnos['message']
                ?? 'No fue posible obtener los alumnos.',
        ]);
    }


    $alumnosIds =
        $alumnos['data']['alumnos_ids']
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | OBTENER IMÁGENES
    |--------------------------------------------------------------------------
    |
    | Pedimos una imagen adicional.
    | Esto nos permite saber si existe otra página.
    |
    */

    $resultado =
        $this->moodleService
            ->getProctoringImages(
                $token,
                $courseId,
                $cmid,
                $alumnosIds,
                $offset,
                $limit + 1
            );


    if (!$resultado['success']) {

        return response()->json([
            'ok' => false,

            'message' =>
                $resultado['message']
                ?? 'No fue posible obtener las imágenes.',
        ]);
    }


    $imagenes =
        $resultado['data']['imagenes']
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | SABER SI HAY MÁS IMÁGENES
    |--------------------------------------------------------------------------
    */

    $hayMas =
        count($imagenes) > $limit;


    if ($hayMas) {

        $imagenes =
            array_slice(
                $imagenes,
                0,
                $limit
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RESPUESTA
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'ok' => true,

        'imagenes' =>
            $imagenes,

        'cantidad' =>
            count($imagenes),

        'offset' =>
            $offset,

        'limit' =>
            $limit,

        'next_offset' =>
            $offset
            +
            count($imagenes),

        'has_more' =>
            $hayMas,

    ]);
}

/*
|--------------------------------------------------------------------------
| PROBAR CAPTURAS DE PROCTORING
|--------------------------------------------------------------------------
*/

public function probarCapturas(Request $request)
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

        'cmid' =>
            'required|integer',

    ]);


    $token =
        session('moodle_token');


    $userIdProfesor =
        (int) session('moodle_user_id');


    $courseId =
        (int) $request->courseid;


    $quizId =
        (int) $request->quizid;


    $cmid =
        (int) $request->cmid;


    if (!$token || !$userIdProfesor) {

        return response()->json([
            'ok' => false,
            'message' =>
                'No se encontró la sesión de Moodle.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER UN ALUMNO QUE HAYA REALIZADO EL EXAMEN
    |--------------------------------------------------------------------------
    */

    $alumnos =
        $this->moodleService
            ->getQuizStudents(
                $token,
                $courseId,
                $quizId
            );


    if (!$alumnos['success']) {

        return response()->json([
            'ok' => false,
            'message' =>
                $alumnos['message']
                ?? 'No fue posible obtener los alumnos.',
        ]);
    }


    $alumnoId =
        $alumnos['data']['primer_alumno_id']
        ?? null;


    if (!$alumnoId) {

        return response()->json([
            'ok' => false,
            'message' =>
                'No se encontró un alumno con intento en este examen.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CONSULTAR SUS CAPTURAS
    |--------------------------------------------------------------------------
    */

    $capturas =
        $this->moodleService
            ->getProctoringCamshots(
                $token,
                $courseId,
                $cmid,
                (int) $alumnoId
            );


    /*
    |--------------------------------------------------------------------------
    | DEVOLVER RESPUESTA SIN MODIFICAR
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'ok' => true,

        'alumno_id' =>
            (int) $alumnoId,

        'capturas' =>
            $capturas,
    ]);
}

/*
|--------------------------------------------------------------------------
| MOSTRAR IMAGEN DE EVIDENCIA EN EL NAVEGADOR
|--------------------------------------------------------------------------
*/

public function imagen(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDAR SESIÓN
    |--------------------------------------------------------------------------
    */

    if (!session('moodle_authenticated')) {

        abort(401);
    }


    $token =
        session('moodle_token');


    if (!$token) {

        abort(401);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER URL ORIGINAL
    |--------------------------------------------------------------------------
    */

    $url =
        trim(
            (string) $request->query(
                'url',
                ''
            )
        );


    if (
        $url === ''
        ||
        !filter_var(
            $url,
            FILTER_VALIDATE_URL
        )
    ) {

        abort(400);
    }


    /*
    |--------------------------------------------------------------------------
    | SEGURIDAD
    |--------------------------------------------------------------------------
    |
    | Solo permitimos imágenes provenientes
    | del Moodle de la UTM.
    |
    */

    $host =
        strtolower(
            (string) parse_url(
                $url,
                PHP_URL_HOST
            )
        );


    $path =
        (string) parse_url(
            $url,
            PHP_URL_PATH
        );


    if (
        $host !== 'cv.utm.mx'
        ||
        strpos(
            $path,
            '/pluginfile.php/'
        ) !== 0
    ) {

        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER IMAGEN DESDE MOODLE
    |--------------------------------------------------------------------------
    */

    $archivo =
        $this->obtenerArchivoMoodle(
            $url,
            $token
        );


    if (!$archivo['ok']) {

        abort(
            502,
            $archivo['message']
            ?? 'No fue posible obtener la evidencia.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEVOLVER IMAGEN AL NAVEGADOR
    |--------------------------------------------------------------------------
    */

    return response(
        $archivo['body'],
        200
    )
    ->header(
        'Content-Type',
        $archivo['content_type']
    )
    ->header(
        'Cache-Control',
        'private, max-age=300'
    );
}


/*
|--------------------------------------------------------------------------
| OBTENER ARCHIVO DE EVIDENCIA DESDE MOODLE
|--------------------------------------------------------------------------
|
| Moodle nos entrega una URL pluginfile.php.
| Para descargarla desde Laravel usamos el endpoint
| webservice/pluginfile.php y el token del profesor.
|
*/

private function obtenerArchivoMoodle(
    string $url,
    string $token
): array {

    /*
    |--------------------------------------------------------------------------
    | CONVERTIR URL A WEBSERVICE
    |--------------------------------------------------------------------------
    */

    $urlWebService =
        preg_replace(
            '#/pluginfile\.php/#',
            '/webservice/pluginfile.php/',
            $url,
            1
        );


    if (!$urlWebService) {

        return [
            'ok' => false,
            'message' =>
                'La URL de la evidencia no es válida.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | AGREGAR TOKEN
    |--------------------------------------------------------------------------
    */

    $separador =
        strpos(
            $urlWebService,
            '?'
        ) === false
            ? '?'
            : '&';


    $urlWebService .=
        $separador
        .
        'token='
        .
        urlencode($token);


    /*
    |--------------------------------------------------------------------------
    | DESCARGAR
    |--------------------------------------------------------------------------
    */

    try {

        $response =
            Http::withOptions([
                'verify' =>
                    config(
                        'moodle.verify_ssl'
                    ),
            ])
            ->timeout(30)
            ->get(
                $urlWebService
            );


        if (!$response->successful()) {

            return [
                'ok' => false,
                'message' =>
                    'Moodle no permitió descargar la evidencia.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR QUE SEA UNA IMAGEN
        |--------------------------------------------------------------------------
        */

        $contentType =
            strtolower(
                (string)
                $response->header(
                    'Content-Type'
                )
            );


        if (
            strpos(
                $contentType,
                'image/'
            ) !== 0
        ) {

            return [
                'ok' => false,
                'message' =>
                    'Moodle no devolvió una imagen válida.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return [
            'ok' => true,

            'body' =>
                $response->body(),

            'content_type' =>
                $contentType,
        ];


    } catch (\Throwable $error) {

        return [
            'ok' => false,

            'message' =>
                'No fue posible obtener la evidencia desde Moodle.',
        ];
    }
}

public function descargar(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDAR SESIÓN
    |--------------------------------------------------------------------------
    */

    if (!session('moodle_authenticated')) {

        return response()->json(
            [
                'ok' => false,
                'message' =>
                    'La sesión de Moodle no es válida.',
            ],
            401
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR DATOS
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
    | DATOS DE LA SOLICITUD
    |--------------------------------------------------------------------------
    */

    $token =
        session(
            'moodle_token'
        );


    $profesorId =
        (int) session(
            'moodle_user_id'
        );


    $courseId =
        (int) $request->courseid;


    $quizId =
        (int) $request->quizid;


    $groupId =
        $request->filled(
            'groupid'
        )
            ? (int) $request->groupid
            : 0;


    if (
        !$token
        ||
        !$profesorId
    ) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No se encontró la sesión de Moodle.',
            ],
            401
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR ACCESO AL CURSO
    |--------------------------------------------------------------------------
    */

    if (
        !$this->profesorTieneCurso(
            $token,
            $profesorId,
            $courseId
        )
    ) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No tienes acceso a este curso.',
            ],
            403
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER EXAMEN
    |--------------------------------------------------------------------------
    */

    $examenes =
        $this->moodleService
            ->getCourseQuizzes(
                $token,
                $courseId
            );


    if (!$examenes['success']) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    $examenes['message']
                    ??
                    'No fue posible obtener el examen.',
            ],
            502
        );
    }


    $cmid = 0;

    $nombreExamen =
        'Examen';


    foreach (
        $examenes['data'] ?? []
        as $examen
    ) {

        if (
            (int) (
                $examen['id']
                ?? 0
            )
            !==
            $quizId
        ) {

            continue;
        }


        $cmid =
            (int) (
                $examen['cmid']
                ?? 0
            );


        $nombreExamen =
            trim(
                (string) (
                    $examen['nombre']
                    ?? 'Examen'
                )
            );


        break;
    }


    if ($cmid <= 0) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No se encontró el módulo del examen seleccionado.',
            ],
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER ALUMNOS
    |--------------------------------------------------------------------------
    */

    $alumnos =
        $this->moodleService
            ->getQuizStudents(
                $token,
                $courseId,
                $quizId
            );


    if (!$alumnos['success']) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    $alumnos['message']
                    ??
                    'No fue posible obtener los alumnos.',
            ],
            502
        );
    }


    $alumnosIds =
        $alumnos['data']['alumnos_ids']
        ?? [];


    $usuariosAlumnos =
        $alumnos['data']['usuarios']
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | FILTRAR POR GRUPO
    |--------------------------------------------------------------------------
    */

    if ($groupId > 0) {

        $usuariosGrupo =
            $this->moodleService
                ->getGroupUserIds(
                    $token,
                    $groupId
                );


        if (!$usuariosGrupo['success']) {

            return response()->json(
                [
                    'ok' => false,

                    'message' =>
                        $usuariosGrupo['message']
                        ??
                        'No fue posible obtener los alumnos del grupo.',
                ],
                502
            );
        }


        $alumnosIds =
            array_values(
                array_intersect(
                    $alumnosIds,
                    $usuariosGrupo['data']
                    ?? []
                )
            );
    }


    if (empty($alumnosIds)) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No se encontraron alumnos con intento para los filtros seleccionados.',
            ],
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CARPETA DEL ZIP
    |--------------------------------------------------------------------------
    */

    $carpetaTemporal =
        storage_path(
            'app/evidencias_zip'
        );


    if (!is_dir($carpetaTemporal)) {

        if (
            !mkdir(
                $carpetaTemporal,
                0755,
                true
            )
            &&
            !is_dir(
                $carpetaTemporal
            )
        ) {

            return response()->json(
                [
                    'ok' => false,

                    'message' =>
                        'No fue posible crear la carpeta temporal.',
                ],
                500
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | IDENTIFICADOR DEL PROCESO
    |--------------------------------------------------------------------------
    */

    $jobId =
        (string) Str::uuid();


    /*
    |--------------------------------------------------------------------------
    | NOMBRE DEL ZIP
    |--------------------------------------------------------------------------
    */

    $nombreSeguro =
        Str::slug(
            $nombreExamen,
            '_'
        );


    if ($nombreSeguro === '') {

        $nombreSeguro =
            'examen';
    }


    $nombreZip =
        'Evidencias_'
        .
        $nombreSeguro
        .
        '.zip';


    /*
    |--------------------------------------------------------------------------
    | RUTA FÍSICA DEL ZIP
    |--------------------------------------------------------------------------
    */

    $rutaZip =
        $carpetaTemporal
        .
        DIRECTORY_SEPARATOR
        .
        'sgae_'
        .
        $jobId
        .
        '.zip';


    /*
    |--------------------------------------------------------------------------
    | PROGRESO INICIAL
    |--------------------------------------------------------------------------
    */

    Cache::put(
        'sgae_download_progress_'
        .
        $jobId,
        [
            'estado' =>
                'pendiente',

            'fase' =>
                'Preparando descarga...',

            'porcentaje' =>
                0,

            'actual' =>
                0,

            'total' =>
                0,

            'archivo_actual' =>
                '',
        ],
        now()->addHours(
            2
        )
    );


    /*
    |--------------------------------------------------------------------------
    | INFORMACIÓN PRIVADA DEL PROCESO
    |--------------------------------------------------------------------------
    |
    | Esto también nos permite verificar
    | que otro profesor no pueda consultar
    | o descargar este ZIP.
    |
    */

    Cache::put(
        'sgae_download_meta_'
        .
        $jobId,
        [
            'profesor_id' =>
                $profesorId,

            'courseid' =>
                $courseId,

            'quizid' =>
                $quizId,

            'groupid' =>
                $groupId,

            'cmid' =>
                $cmid,

            'nombre_zip' =>
                $nombreZip,

            'ruta_zip' =>
                $rutaZip,
        ],
        now()->addHours(
            2
        )
    );


    /*
    |--------------------------------------------------------------------------
    | ENVIAR AL WORKER
    |--------------------------------------------------------------------------
    */

    try {

        PrepararDescargaEvidencias::dispatch(

            $jobId,

            encrypt(
                $token
            ),

            $courseId,

            $cmid,

            $alumnosIds,

            $usuariosAlumnos,

            $nombreExamen,

            $rutaZip,

            $nombreZip
        );


    } catch (\Throwable $error) {

        Cache::forget(
            'sgae_download_progress_'
            .
            $jobId
        );


        Cache::forget(
            'sgae_download_meta_'
            .
            $jobId
        );


        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No fue posible iniciar la descarga.',
            ],
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONDER INMEDIATAMENTE
    |--------------------------------------------------------------------------
    |
    | Laravel ya no espera a que se descarguen
    | las 2,000 o 3,000 imágenes.
    |
    | El Job continúa trabajando por separado.
    |
    */

    return response()->json([
        'ok' =>
            true,

        'job_id' =>
            $jobId,

        'message' =>
            'La descarga fue iniciada.',
    ]);
}

public function progresoDescarga(
    string $jobId
) {
    /*
    |--------------------------------------------------------------------------
    | VALIDAR SESIÓN
    |--------------------------------------------------------------------------
    */

    if (!session('moodle_authenticated')) {

        return response()->json(
            [
                'estado' =>
                    'error',

                'mensaje' =>
                    'Sesión no válida.',
            ],
            401
        );
    }


    $profesorId =
        (int) session(
            'moodle_user_id'
        );


    /*
    |--------------------------------------------------------------------------
    | METADATOS DEL PROCESO
    |--------------------------------------------------------------------------
    */

    $meta =
        Cache::get(
            'sgae_download_meta_'
            .
            $jobId
        );


    if (
        !$meta
        ||
        (int) (
            $meta['profesor_id']
            ?? 0
        )
        !==
        $profesorId
    ) {

        return response()->json(
            [
                'estado' =>
                    'error',

                'mensaje' =>
                    'No se encontró esta descarga.',
            ],
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESO
    |--------------------------------------------------------------------------
    */

    $progreso =
        Cache::get(
            'sgae_download_progress_'
            .
            $jobId
        );


    if (!$progreso) {

        $progreso = [
            'estado' =>
                'pendiente',

            'fase' =>
                'Esperando inicio...',

            'porcentaje' =>
                0,

            'actual' =>
                0,

            'total' =>
                0,

            'archivo_actual' =>
                '',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | SI TERMINÓ
    |--------------------------------------------------------------------------
    */

    if (
        (
            $progreso['estado']
            ?? ''
        )
        ===
        'completado'
    ) {

        $rutaZip =
            $progreso['ruta_zip']
            ??
            $meta['ruta_zip']
            ??
            null;


        $nombreZip =
            $progreso['nombre_zip']
            ??
            $meta['nombre_zip']
            ??
            'Evidencias.zip';


        if (
            $rutaZip
            &&
            is_file(
                $rutaZip
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | ELIMINAR ZIP ANTERIOR
            |--------------------------------------------------------------------------
            */

            $zipAnterior =
                session(
                    'evidencias_zip_actual'
                );


            if ($zipAnterior) {

                $rutaAnterior =
                    storage_path(
                        'app/evidencias_zip/'
                        .
                        basename(
                            (string)
                            $zipAnterior
                        )
                    );


                if (
                    $rutaAnterior !==
                    $rutaZip
                    &&
                    is_file(
                        $rutaAnterior
                    )
                ) {

                    @unlink(
                        $rutaAnterior
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | GUARDAR ZIP PARA EL ANÁLISIS
            |--------------------------------------------------------------------------
            */

            session([

                'evidencias_zip_actual' =>
                    basename(
                        $rutaZip
                    ),

                'evidencias_zip_nombre' =>
                    $nombreZip,

                'evidencias_zip_courseid' =>
                    $meta['courseid']
                    ?? 0,

                'evidencias_zip_quizid' =>
                    $meta['quizid']
                    ?? 0,

                'evidencias_zip_groupid' =>
                    $meta['groupid']
                    ?? 0,

                'evidencias_zip_cmid' =>
                    $meta['cmid']
                    ?? 0,

                'evidencias_zip_generado_en' =>
                    time(),
            ]);


            $progreso[
                'archivo_disponible'
            ] =
                true;

        } else {

            $progreso[
                'archivo_disponible'
            ] =
                false;
        }
    }


    return response()->json(
        $progreso
    );
}


public function archivoDescarga(
    string $jobId
) {
    /*
    |--------------------------------------------------------------------------
    | VALIDAR SESIÓN
    |--------------------------------------------------------------------------
    */

    if (!session('moodle_authenticated')) {
        abort(401);
    }


    $profesorId =
        (int) session(
            'moodle_user_id'
        );


    /*
    |--------------------------------------------------------------------------
    | VALIDAR PROPIETARIO
    |--------------------------------------------------------------------------
    */

    $meta =
        Cache::get(
            'sgae_download_meta_'
            .
            $jobId
        );


    if (
        !$meta
        ||
        (int) (
            $meta['profesor_id']
            ?? 0
        )
        !==
        $profesorId
    ) {

        abort(
            404,
            'No se encontró la descarga.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR QUE TERMINÓ
    |--------------------------------------------------------------------------
    */

    $progreso =
        Cache::get(
            'sgae_download_progress_'
            .
            $jobId
        );


    if (
        !$progreso
        ||
        (
            $progreso['estado']
            ?? ''
        )
        !==
        'completado'
    ) {

        abort(
            409,
            'La descarga todavía no está preparada.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ARCHIVO
    |--------------------------------------------------------------------------
    */

    $rutaZip =
        $progreso['ruta_zip']
        ??
        $meta['ruta_zip']
        ??
        null;


    $nombreZip =
        $progreso['nombre_zip']
        ??
        $meta['nombre_zip']
        ??
        'Evidencias.zip';


    if (
        !$rutaZip
        ||
        !is_file(
            $rutaZip
        )
    ) {

        abort(
            404,
            'El archivo ZIP ya no está disponible.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEGURIDAD DE RUTA
    |--------------------------------------------------------------------------
    */

    $directorioPermitido =
        realpath(
            storage_path(
                'app/evidencias_zip'
            )
        );


    $archivoReal =
        realpath(
            $rutaZip
        );


    if (
        !$directorioPermitido
        ||
        !$archivoReal
        ||
        strpos(
            $archivoReal,
            $directorioPermitido
            .
            DIRECTORY_SEPARATOR
        ) !== 0
    ) {

        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | ENTREGAR ZIP
    |--------------------------------------------------------------------------
    |
    | NO se elimina porque después
    | se utiliza para el análisis.
    |
    */

    return response()
        ->download(
            $archivoReal,
            $nombreZip,
            [
                'Content-Type' =>
                    'application/zip',
            ]
        );
}


/*
|--------------------------------------------------------------------------
| INICIAR ANÁLISIS
|--------------------------------------------------------------------------
*/

public function iniciarAnalisis(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDAR SESIÓN
    |--------------------------------------------------------------------------
    */

    if (!session('moodle_authenticated')) {

        return redirect()
            ->route('login');
    }


    /*
    |--------------------------------------------------------------------------
    | ARCHIVO A ANALIZAR
    |--------------------------------------------------------------------------
    |
    | Hay dos posibilidades:
    |
    | 1. El usuario seleccionó manualmente un ZIP.
    | 2. El ZIP acaba de ser generado por SGAE.
    |
    */

    $archivoSubido =
        $request->file(
            'archivo'
        );


    $rutaZip =
        null;


    $nombreZip =
        null;


    /*
    |--------------------------------------------------------------------------
    | OPCIÓN 1: ARCHIVO SUBIDO MANUALMENTE
    |--------------------------------------------------------------------------
    */

    if ($archivoSubido) {

        $request->validate([
            'archivo' => [
                'file',
                'mimes:zip',
            ],
        ]);


        $rutaZip =
            $archivoSubido
                ->getRealPath();


        $nombreZip =
            $archivoSubido
                ->getClientOriginalName();

    } else {

        /*
        |--------------------------------------------------------------------------
        | OPCIÓN 2: ZIP GENERADO POR SGAE
        |--------------------------------------------------------------------------
        */

        $zipSesion =
            session(
                'evidencias_zip_actual'
            );


        $nombreZip =
            session(
                'evidencias_zip_nombre'
            );


        /*
         * No existe ninguna descarga previa.
         */
        if (!$zipSesion) {

            return back()
                ->withErrors([
                    'archivo' =>
                        'No existe una descarga de evidencias disponible para analizar.',
                ]);
        }


        /*
         * Construimos la ruta exclusivamente
         * dentro de storage/app/evidencias_zip.
         */
        $rutaZip =
            storage_path(
                'app/evidencias_zip/'
                .
                basename(
                    (string) $zipSesion
                )
            );


        /*
         * Verificar que el ZIP siga existiendo.
         */
        if (
            !is_file(
                $rutaZip
            )
        ) {

            return back()
                ->withErrors([
                    'archivo' =>
                        'El archivo de evidencias ya no está disponible. Realiza nuevamente la descarga.',
                ]);
        }


        /*
         * Nombre que recibirá FastAPI.
         */
        if (!$nombreZip) {

            $nombreZip =
                basename(
                    $rutaZip
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ABRIR ZIP
    |--------------------------------------------------------------------------
    */

    $stream =
        fopen(
            $rutaZip,
            'r'
        );


    if (!$stream) {

        return back()
            ->withErrors([
                'archivo' =>
                    'No fue posible abrir el archivo de evidencias.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ENVIAR ZIP A FASTAPI
    |--------------------------------------------------------------------------
    */

    try {

        $respuesta =
            Http::timeout(60)
                ->attach(
                    'archivo',
                    $stream,
                    $nombreZip
                )
                ->post(
                    'http://127.0.0.1:8000/analizar/iniciar'
                );


    } catch (\Throwable $error) {

        return back()
            ->withErrors([
                'archivo' =>
                    'No fue posible conectar con la API de análisis: '
                    .
                    $error->getMessage(),
            ]);


    } finally {

        if (
            is_resource(
                $stream
            )
        ) {

            fclose(
                $stream
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR RESPUESTA DE FASTAPI
    |--------------------------------------------------------------------------
    */

    if (
        !$respuesta->successful()
    ) {

        return back()
            ->withErrors([
                'archivo' =>
                    'La API no pudo iniciar el análisis.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | LEER RESPUESTA
    |--------------------------------------------------------------------------
    */

    $datos =
        $respuesta->json();


    if (
        !isset(
            $datos['job_id']
        )
        ||
        !isset(
            $datos['archivo']
        )
    ) {

        return back()
            ->withErrors([
                'archivo' =>
                    'La API devolvió una respuesta inválida.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR TRABAJO ACTUAL
    |--------------------------------------------------------------------------
    */

    session([

        'analisis_job_id' =>
            $datos['job_id'],

        'analisis_archivo' =>
            $datos['archivo'],

    ]);


    /*
    |--------------------------------------------------------------------------
    | PANTALLA DE PROGRESO DEL ANÁLISIS
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'evidencias.analizando'
        );
}


/*
|--------------------------------------------------------------------------
| CONSULTAR PROGRESO DEL ANÁLISIS
|--------------------------------------------------------------------------
*/

public function progresoAnalisis()
{
    /*
    |--------------------------------------------------------------------------
    | JOB ACTUAL
    |--------------------------------------------------------------------------
    */

    $jobId =
        session(
            'analisis_job_id'
        );


    if (!$jobId) {

        return response()->json(
            [
                'estado' =>
                    'error',

                'mensaje' =>
                    'No existe un análisis activo.',
            ],
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONSULTAR FASTAPI
    |--------------------------------------------------------------------------
    */

    try {

        $respuesta =
            Http::timeout(15)
                ->get(
                    'http://127.0.0.1:8000'
                    .
                    '/analizar/progreso/'
                    .
                    $jobId
                );


    } catch (\Throwable $error) {

        return response()->json(
            [
                'estado' =>
                    'error',

                'mensaje' =>
                    'No fue posible conectar con la API.',
            ],
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    if (
        !$respuesta->successful()
    ) {

        return response()->json(
            [
                'estado' =>
                    'error',

                'mensaje' =>
                    'No fue posible consultar el progreso.',
            ],
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESO
    |--------------------------------------------------------------------------
    */

    $progreso =
        $respuesta->json();


    /*
    |--------------------------------------------------------------------------
    | SI TERMINÓ, OBTENER RESULTADO
    |--------------------------------------------------------------------------
    */

    if (
        isset(
            $progreso['estado']
        )
        &&
        $progreso['estado']
        ===
        'completado'
    ) {

        try {

            $respuestaResultado =
                Http::timeout(15)
                    ->get(
                        'http://127.0.0.1:8000'
                        .
                        '/analizar/resultado/'
                        .
                        $jobId
                    );


            if (
                $respuestaResultado
                    ->successful()
            ) {

                $resultado =
    $respuestaResultado
        ->json();

session([
    'analisis_resultado' =>
        $resultado,
]);

$this->guardarAnalisisHistorial(
    $jobId,
    $resultado
);
            }


        } catch (\Throwable $error) {

            /*
             * El progreso ya terminó.
             * El resultado podrá
             * consultarse después.
             */

        }
    }


    /*
    |--------------------------------------------------------------------------
    | DEVOLVER PROGRESO
    |--------------------------------------------------------------------------
    */

    return response()->json(
        $progreso
    );
}

private function guardarAnalisisHistorial(
    string $jobId,
    array $resultado
): void {

    /*
     * Si este análisis ya fue guardado,
     * no volver a registrarlo.
     */
    if (
        AnalisisHistorial::where(
            'job_id',
            $jobId
        )->exists()
    ) {
        return;
    }


    try {

        /*
         * Obtener el PDF generado por FastAPI.
         */
        $respuestaPdf =
            Http::timeout(60)
                ->get(
                    'http://127.0.0.1:8000'
                    .
                    '/analizar/reporte/'
                    .
                    $jobId
                );


        if (!$respuestaPdf->successful()) {
            return;
        }


        /*
         * Nombre del ZIP analizado.
         */
        $nombreArchivo =
            session(
                'analisis_archivo',
                $resultado['archivo']
                    ?? 'Analisis.zip'
            );


        /*
         * Nombre sin extensión.
         */
        $nombreCarpeta =
            pathinfo(
                $nombreArchivo,
                PATHINFO_FILENAME
            );


        /*
         * Nombre del PDF.
         */
        $nombrePdf =
            $resultado['reporte']['nombre']
            ??
            (
                'Reporte_'
                .
                $jobId
                .
                '.pdf'
            );


        /*
         * Ruta permanente dentro de Laravel.
         */
        $rutaPdf =
            'reportes/historial/'
            .
            $jobId
            .
            '/'
            .
            $nombrePdf;


        /*
         * Guardar copia física del PDF.
         */
        Storage::disk('local')->put(
            $rutaPdf,
            $respuestaPdf->body()
        );


        /*
         * Guardar información en SQLite.
         */
        AnalisisHistorial::create([

            'job_id' =>
                $jobId,

            'moodle_username' =>
                session('moodle_username'),

            'nombre_archivo' =>
                $nombreArchivo,

            'nombre_carpeta' =>
                $nombreCarpeta,

            'fecha_analisis' =>
                $resultado['fecha_analisis']
                ?? now(),

            'total_imagenes' =>
                $resultado['total_imagenes']
                ?? 0,

            'total_carpetas' =>
                $resultado['total_alumnos']
                ?? 0,

            'nivel_confianza' =>
                $resultado['nivel_confianza_general']
                ?? null,

            'porcentaje_confianza' =>
                $resultado['porcentaje_confianza_general']
                ?? null,

            'ruta_pdf' =>
                $rutaPdf,

        ]);


    } catch (\Throwable $error) {

        /*
         * Un error al guardar el historial
         * no debe interrumpir el análisis.
         */

    }
}

/*
|--------------------------------------------------------------------------
| MOSTRAR / DESCARGAR REPORTE ACTUAL
|--------------------------------------------------------------------------
*/

public function reporteActual(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | JOB ACTUAL
    |--------------------------------------------------------------------------
    */

    $jobId =
        session(
            'analisis_job_id'
        );


    if (!$jobId) {

        abort(
            404,
            'No existe un reporte disponible.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PEDIR PDF A FASTAPI
    |--------------------------------------------------------------------------
    */

    try {

        $respuesta =
            Http::timeout(60)
                ->get(
                    'http://127.0.0.1:8000'
                    .
                    '/analizar/reporte/'
                    .
                    $jobId
                );


    } catch (\Throwable $error) {

        abort(
            500,
            'No fue posible conectar con la API.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR PDF
    |--------------------------------------------------------------------------
    */

    if (
        !$respuesta->successful()
    ) {

        abort(
            404,
            'No fue posible obtener el reporte.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NOMBRE DEL PDF
    |--------------------------------------------------------------------------
    */

    $resultado =
        session(
            'analisis_resultado',
            []
        );


    $nombrePdf =
        $resultado['reporte']['nombre']
        ??
        'Reporte_Analisis.pdf';


    /*
    |--------------------------------------------------------------------------
    | MOSTRAR O DESCARGAR
    |--------------------------------------------------------------------------
    */

    $descargar =
        $request->query(
            'download'
        ) == '1';


    $disposicion =
        $descargar
            ? 'attachment'
            : 'inline';


    /*
    |--------------------------------------------------------------------------
    | RESPUESTA PDF
    |--------------------------------------------------------------------------
    */

    return response(
        $respuesta->body(),
        200,
        [
            'Content-Type' =>
                'application/pdf',

            'Content-Disposition' =>
                $disposicion
                .
                '; filename="'
                .
                $nombrePdf
                .
                '"',
        ]
    );
}

public function historial()
{
    if (!session('moodle_authenticated')) {

        return redirect()
            ->route('login');
    }


    $username =
        session('moodle_username');


    $registros =
        AnalisisHistorial::where(
            'moodle_username',
            $username
        )
        ->orderBy(
            'fecha_analisis',
            'desc'
        )
        ->get();


    $analisis =
        $registros
            ->map(function ($item) {

                return [

                    'id' =>
                        $item->id,

                    'nombre' =>
                        $item->nombre_carpeta,

                    'fecha' =>
                        $item->fecha_analisis
                            ? $item
                                ->fecha_analisis
                                ->format('d/m/Y H:i')
                            : '',

                    'imagenes' =>
                        $item->total_imagenes,

                    'carpetas' =>
                        $item->total_carpetas,

                ];

            })
            ->all();


    return view(
        'evidencias.historial',
        compact('analisis')
    );
}

public function reporteHistorial($id)
{
    if (!session('moodle_authenticated')) {

        return redirect()
            ->route('login');
    }


    $registro =
        AnalisisHistorial::where(
            'id',
            $id
        )
        ->where(
            'moodle_username',
            session('moodle_username')
        )
        ->firstOrFail();


    $archivo =
        storage_path(
            'app/'
            .
            $registro->ruta_pdf
        );


    if (!is_file($archivo)) {

        abort(
            404,
            'No se encontró el reporte PDF.'
        );
    }


    return response()->file(
        $archivo,
        [
            'Content-Type' =>
                'application/pdf',

            'Content-Disposition' =>
                'inline; filename="'
                .
                basename($archivo)
                .
                '"',
        ]
    );
}

}