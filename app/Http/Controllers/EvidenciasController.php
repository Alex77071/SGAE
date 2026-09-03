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

    /*
    |--------------------------------------------------------------------------
    | DESCARGAR EVIDENCIAS
    |--------------------------------------------------------------------------
    */



 public function descargar(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | PERMITIR DESCARGAS GRANDES
    |--------------------------------------------------------------------------
    |
    | Algunos exámenes pueden tener cientos
    | o miles de evidencias.
    |
    */

    @set_time_limit(0);


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
    | VERIFICAR SOPORTE ZIP
    |--------------------------------------------------------------------------
    */

    if (
        !class_exists(
            \ZipArchive::class
        )
    ) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'La extensión ZIP de PHP no está habilitada.',
            ],
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATOS
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


    /*
    |--------------------------------------------------------------------------
    | VALIDAR TOKEN
    |--------------------------------------------------------------------------
    */

    if (
        !$token ||
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
    | VERIFICAR QUE EL PROFESOR TENGA EL CURSO
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
    | OBTENER INFORMACIÓN DEL EXAMEN
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
                    ?? 'No fue posible obtener el examen.',
            ],
            502
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR CMID Y NOMBRE
    |--------------------------------------------------------------------------
    */

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
                $examen['nombre']
                ?? 'Examen'
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
    | OBTENER ALUMNOS CON INTENTO
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
                    ?? 'No fue posible obtener los alumnos.',
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
    |
    | Si groupid es 0 significa:
    |
    | Todos los grupos
    |
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
                        ?? 'No fue posible obtener los alumnos del grupo.',
                ],
                502
            );
        }


        /*
         * Solo conservamos estudiantes que:
         *
         * 1. realizaron el examen
         * 2. pertenecen al grupo
         */

        $alumnosIds =
            array_values(
                array_intersect(
                    $alumnosIds,
                    $usuariosGrupo['data']
                    ?? []
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SIN ALUMNOS
    |--------------------------------------------------------------------------
    */

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
    | OBTENER TODAS LAS EVIDENCIAS
    |--------------------------------------------------------------------------
    |
    | El modal usa bloques de 24.
    |
    | Para el ZIP necesitamos todas.
    |
    */

    $resultadoImagenes =
        $this->moodleService
            ->getProctoringImages(
                $token,
                $courseId,
                $cmid,
                $alumnosIds,
                0,
                PHP_INT_MAX
            );


    if (!$resultadoImagenes['success']) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    $resultadoImagenes['message']
                    ?? 'No fue posible obtener las evidencias.',
            ],
            502
        );
    }


    $imagenes =
        $resultadoImagenes['data']['imagenes']
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | SIN IMÁGENES
    |--------------------------------------------------------------------------
    */

    if (empty($imagenes)) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'El examen seleccionado no contiene evidencias para descargar.',
            ],
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CARPETA TEMPORAL PARA ZIPS
    |--------------------------------------------------------------------------
    */

    $carpetaTemporal =
        storage_path(
            'app/evidencias_zip'
        );


    if (!is_dir($carpetaTemporal)) {

        $creada =
            mkdir(
                $carpetaTemporal,
                0755,
                true
            );


        if (
            !$creada &&
            !is_dir($carpetaTemporal)
        ) {

            return response()->json(
                [
                    'ok' => false,

                    'message' =>
                        'No fue posible crear la carpeta temporal del ZIP.',
                ],
                500
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NOMBRE DEL EXAMEN SEGURO
    |--------------------------------------------------------------------------
    */

    $nombreSeguro =
        \Illuminate\Support\Str::slug(
            $nombreExamen,
            '_'
        );


    if ($nombreSeguro === '') {

        $nombreSeguro =
            'examen';

    }


    /*
    |--------------------------------------------------------------------------
    | NOMBRE FÍSICO TEMPORAL
    |--------------------------------------------------------------------------
    */

    $rutaZip =
        $carpetaTemporal
        .
        DIRECTORY_SEPARATOR
        .
        uniqid(
            'sgae_',
            true
        )
        .
        '.zip';


    /*
    |--------------------------------------------------------------------------
    | NOMBRE QUE RECIBIRÁ EL USUARIO
    |--------------------------------------------------------------------------
    */

    $nombreZip =
        'Evidencias_'
        .
        $nombreSeguro
        .
        '.zip';


    /*
    |--------------------------------------------------------------------------
    | CREAR ZIP
    |--------------------------------------------------------------------------
    */

    $zip =
        new \ZipArchive();


    $resultadoZip =
        $zip->open(
            $rutaZip,
            \ZipArchive::CREATE
            |
            \ZipArchive::OVERWRITE
        );


    if ($resultadoZip !== true) {

        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No fue posible crear el archivo ZIP.',
            ],
            500
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONTADORES
    |--------------------------------------------------------------------------
    */

    $contadorAlumno = [];

    $imagenesDescargadas = 0;

    $imagenesFallidas = 0;


    /*
    |--------------------------------------------------------------------------
    | RECORRER EVIDENCIAS
    |--------------------------------------------------------------------------
    */

    foreach ($imagenes as $imagen) {

        $url =
            trim(
                $imagen['url']
                ?? ''
            );


        $alumnoId =
            (int) (
                $imagen['userid']
                ?? 0
            );


        if (
            $url === ''
            ||
            $alumnoId <= 0
        ) {

            $imagenesFallidas++;

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | NUMERACIÓN POR ALUMNO
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
                $contadorAlumno[
                    $alumnoId
                ]
            )
        ) {

            $contadorAlumno[
                $alumnoId
            ] = 0;
        }


        $contadorAlumno[
            $alumnoId
        ]++;


        /*
        |--------------------------------------------------------------------------
        | DESCARGAR DE MOODLE
        |--------------------------------------------------------------------------
        */

        $archivo =
            $this->obtenerArchivoMoodle(
                $url,
                $token
            );


        if (!$archivo['ok']) {

            $imagenesFallidas++;

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | DETECTAR EXTENSIÓN
        |--------------------------------------------------------------------------
        */

        $contentType =
            strtolower(
                $archivo['content_type']
                ?? ''
            );


        if (
            strpos(
                $contentType,
                'image/png'
            ) !== false
        ) {

            $extension =
                'png';

        } elseif (
            strpos(
                $contentType,
                'image/webp'
            ) !== false
        ) {

            $extension =
                'webp';

        } elseif (
            strpos(
                $contentType,
                'image/bmp'
            ) !== false
        ) {

            $extension =
                'bmp';

        } else {

            /*
             * jpg y jpeg terminarán como jpg.
             */
            $extension =
                'jpg';

        }


        /*
        |--------------------------------------------------------------------------
        | CARPETA DEL ALUMNO DENTRO DEL ZIP
        |--------------------------------------------------------------------------
        */
/*
|--------------------------------------------------------------------------
| USERNAME DEL ALUMNO
|--------------------------------------------------------------------------
*/

$usernameAlumno =
    trim(
        (string) (
            $usuariosAlumnos[
                $alumnoId
            ]
            ?? ''
        )
    );


/*
|--------------------------------------------------------------------------
| LIMPIAR USERNAME PARA USARLO COMO CARPETA
|--------------------------------------------------------------------------
|
| Evitamos caracteres que puedan causar
| problemas en Windows/macOS.
|
*/

$usernameSeguro =
    preg_replace(
        '/[^A-Za-z0-9._-]+/',
        '_',
        $usernameAlumno
    );


$usernameSeguro =
    trim(
        (string) $usernameSeguro,
        '._-'
    );


/*
|--------------------------------------------------------------------------
| NOMBRE DE LA CARPETA
|--------------------------------------------------------------------------
*/

$carpetaAlumno =
    $usernameSeguro !== ''
        ? $usernameSeguro
        : 'alumno_' . $alumnoId;


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DE LA IMAGEN
        |--------------------------------------------------------------------------
        */

        $nombreImagen =
            sprintf(
                'evidencia_%04d.%s',
                $contadorAlumno[
                    $alumnoId
                ],
                $extension
            );


        /*
        |--------------------------------------------------------------------------
        | RUTA DENTRO DEL ZIP
        |--------------------------------------------------------------------------
        */

        $rutaDentroZip =
            $carpetaAlumno
            .
            '/'
            .
            $nombreImagen;


        /*
        |--------------------------------------------------------------------------
        | AGREGAR AL ZIP
        |--------------------------------------------------------------------------
        */

        $agregada =
            $zip->addFromString(
                $rutaDentroZip,
                $archivo['body']
            );


        if ($agregada) {

            $imagenesDescargadas++;

        } else {

            $imagenesFallidas++;

        }
    }


    /*
    |--------------------------------------------------------------------------
    | AGREGAR RESUMEN
    |--------------------------------------------------------------------------
    */

    $resumen =
        "Sistema de Gestión y Análisis de Evidencias"
        .
        PHP_EOL
        .
        PHP_EOL
        .
        "Examen: "
        .
        $nombreExamen
        .
        PHP_EOL
        .
        "Alumnos procesados: "
        .
        count($contadorAlumno)
        .
        PHP_EOL
        .
        "Evidencias encontradas: "
        .
        count($imagenes)
        .
        PHP_EOL
        .
        "Evidencias descargadas: "
        .
        $imagenesDescargadas
        .
        PHP_EOL
        .
        "Evidencias no descargadas: "
        .
        $imagenesFallidas
        .
        PHP_EOL;


    $zip->addFromString(
        '_resumen_descarga.txt',
        $resumen
    );


    /*
    |--------------------------------------------------------------------------
    | CERRAR ZIP
    |--------------------------------------------------------------------------
    */

    $zip->close();


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR QUE HAYA IMÁGENES
    |--------------------------------------------------------------------------
    */

    if ($imagenesDescargadas <= 0) {

        if (file_exists($rutaZip)) {

            @unlink(
                $rutaZip
            );

        }


        return response()->json(
            [
                'ok' => false,

                'message' =>
                    'No fue posible descargar ninguna evidencia desde Moodle.',
            ],
            502
        );
    }
/*
|--------------------------------------------------------------------------
| ELIMINAR ZIP TEMPORAL ANTERIOR
|--------------------------------------------------------------------------
|
| Cada profesor solamente necesita conservar
| el ZIP más reciente para poder analizarlo.
|
*/

$zipAnterior =
    session(
        'evidencias_zip_actual'
    );


if ($zipAnterior) {

    $rutaAnterior =
        $carpetaTemporal
        .
        DIRECTORY_SEPARATOR
        .
        basename(
            (string) $zipAnterior
        );


    if (
        $rutaAnterior !== $rutaZip
        &&
        is_file($rutaAnterior)
    ) {

        @unlink(
            $rutaAnterior
        );

    }
}


/*
|--------------------------------------------------------------------------
| GUARDAR ZIP ACTUAL EN SESIÓN
|--------------------------------------------------------------------------
|
| NO guardamos el ZIP dentro de la sesión.
|
| Solo guardamos el nombre físico del archivo
| para poder localizarlo después desde Laravel.
|
*/

session([
    'evidencias_zip_actual' =>
        basename($rutaZip),

    'evidencias_zip_nombre' =>
        $nombreZip,

    'evidencias_zip_courseid' =>
        $courseId,

    'evidencias_zip_quizid' =>
        $quizId,

    'evidencias_zip_groupid' =>
        $groupId,

    'evidencias_zip_cmid' =>
        $cmid,

    'evidencias_zip_generado_en' =>
        time(),
]);


/*
|--------------------------------------------------------------------------
| ENTREGAR ZIP AL NAVEGADOR
|--------------------------------------------------------------------------
|
| IMPORTANTE:
|
| Ya NO usamos deleteFileAfterSend(true)
| porque necesitaremos este mismo archivo
| para el análisis.
|
*/

return response()
    ->download(
        $rutaZip,
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
                    'http://127.0.0.1:8001'
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

                session([

                    'analisis_resultado' =>
                        $respuestaResultado
                            ->json(),

                ]);
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

}