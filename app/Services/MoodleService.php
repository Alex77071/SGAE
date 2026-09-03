<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MoodleService
{
    /*
    |--------------------------------------------------------------------------
    | AUTENTICAR USUARIO EN MOODLE
    |--------------------------------------------------------------------------
    |
    | Envía usuario y contraseña a Moodle y solicita un token.
    |
    */

    public function authenticate(string $username, string $password): array
    {
        $response = Http::asForm()
            ->withOptions([
                'verify' => config('moodle.verify_ssl'),
            ])
            ->post(
                rtrim(config('moodle.url'), '/') . '/login/token.php',
                [
                    'username' => $username,
                    'password' => $password,
                    'service' => config('moodle.service'),
                ]
            );

        $data = $response->json();

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'No fue posible conectarse con Moodle.',
                'errorcode' => null,
            ];
        }

        if (!is_array($data)) {
            return [
                'success' => false,
                'message' => 'Moodle respondió con un formato no válido.',
                'errorcode' => null,
            ];
        }

        if (empty($data['token'])) {
            return [
                'success' => false,
                'message' => $data['error'] ?? 'Usuario o contraseña incorrectos.',
                'errorcode' => $data['errorcode'] ?? null,
            ];
        }

        return [
            'success' => true,
            'token' => $data['token'],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | LLAMADA GENÉRICA A LA API REST DE MOODLE
    |--------------------------------------------------------------------------
    |
    | Esta función se reutiliza para consultar:
    |
    | - información del usuario
    | - cursos
    | - participantes
    | - roles
    |
    */

    private function call(
        string $token,
        string $function,
        array $parameters = []
    ): array {
        $response = Http::asForm()
            ->withOptions([
                'verify' => config('moodle.verify_ssl'),
            ])
            ->post(
                rtrim(config('moodle.url'), '/') . '/webservice/rest/server.php',
                array_merge(
                    [
                        'wstoken' => $token,
                        'wsfunction' => $function,
                        'moodlewsrestformat' => 'json',
                    ],
                    $parameters
                )
            );

        $data = $response->json();

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'No fue posible consultar Moodle.',
                'errorcode' => null,
            ];
        }

        if (!is_array($data)) {
            return [
                'success' => false,
                'message' => 'Moodle respondió con un formato no válido.',
                'errorcode' => null,
            ];
        }

        if (
            isset($data['exception']) ||
            isset($data['errorcode'])
        ) {
            return [
                'success' => false,
                'message' => $data['message'] ?? 'Moodle devolvió un error.',
                'errorcode' => $data['errorcode'] ?? null,
                'data' => $data,
            ];
        }

        return [
            'success' => true,
            'data' => $data,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER INFORMACIÓN DEL USUARIO AUTENTICADO
    |--------------------------------------------------------------------------
    |
    | Devuelve datos como:
    |
    | userid
    | username
    | fullname
    |
    */

    public function getUserInfo(string $token): array
    {
        return $this->call(
            $token,
            'core_webservice_get_site_info'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SI EL USUARIO ES PROFESOR
    |--------------------------------------------------------------------------
    |
    | La lógica es:
    |
    | 1. Obtener los cursos del usuario.
    | 2. Consultar los participantes de cada curso.
    | 3. Buscar al usuario.
    | 4. Revisar sus roles.
    | 5. Si tiene teacher o editingteacher, permitir acceso.
    |
    */

    public function checkTeacher(
        string $token,
        int $userId
    ): array {
        /*
        |--------------------------------------------------------------------------
        | OBTENER CURSOS DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $coursesResponse = $this->call(
            $token,
            'core_enrol_get_users_courses',
            [
                'userid' => $userId,
            ]
        );

        if (!$coursesResponse['success']) {
            return [
                'success' => false,
                'is_teacher' => false,
                'message' => $coursesResponse['message'],
                'roles' => [],
                'courses' => [],
            ];
        }

        $courses = $coursesResponse['data'];


        /*
        |--------------------------------------------------------------------------
        | ROLES PERMITIDOS
        |--------------------------------------------------------------------------
        */

        $allowedRoles = config(
            'moodle.teacher_roles',
            [
                'editingteacher',
                'teacher',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | RESULTADOS
        |--------------------------------------------------------------------------
        */

        $teacherCourses = [];

        $detectedRoles = [];


        /*
        |--------------------------------------------------------------------------
        | RECORRER CURSOS
        |--------------------------------------------------------------------------
        */

        foreach ($courses as $course) {
            if (empty($course['id'])) {
                continue;
            }

            $courseId = (int) $course['id'];


            /*
            |--------------------------------------------------------------------------
            | CONSULTAR PARTICIPANTES DEL CURSO
            |--------------------------------------------------------------------------
            */

            $usersResponse = $this->call(
                $token,
                'core_enrol_get_enrolled_users',
                [
                    'courseid' => $courseId,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | SI NO PODEMOS CONSULTAR ESE CURSO,
            | CONTINUAMOS CON EL SIGUIENTE
            |--------------------------------------------------------------------------
            */

            if (!$usersResponse['success']) {
                continue;
            }

            $courseUsers = $usersResponse['data'];


            /*
            |--------------------------------------------------------------------------
            | BUSCAR AL USUARIO
            |--------------------------------------------------------------------------
            */

            foreach ($courseUsers as $courseUser) {
                if (
                    (int) ($courseUser['id'] ?? 0)
                    !==
                    $userId
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | OBTENER ROLES DEL USUARIO EN ESE CURSO
                |--------------------------------------------------------------------------
                */

                $roles = $courseUser['roles'] ?? [];


                foreach ($roles as $role) {
                    $shortname = strtolower(
                        trim(
                            $role['shortname'] ?? ''
                        )
                    );

                    if ($shortname === '') {
                        continue;
                    }

                    $roleName =
                        $role['name']
                        ?? $shortname;


                    /*
                    |--------------------------------------------------------------------------
                    | GUARDAR ROL DETECTADO
                    |--------------------------------------------------------------------------
                    */

                    $detectedRoles[$shortname] =
                        $roleName;


                    /*
                    |--------------------------------------------------------------------------
                    | COMPROBAR SI ES PROFESOR
                    |--------------------------------------------------------------------------
                    */

                    if (
                        in_array(
                            $shortname,
                            $allowedRoles,
                            true
                        )
                    ) {
                        $teacherCourses[] = [
                            'id' => $courseId,

                            'name' =>
                                $course['fullname']
                                ?? $course['shortname']
                                ?? 'Curso',

                            'role' =>
                                $roleName,

                            'role_shortname' =>
                                $shortname,
                        ];
                    }
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA FINAL
        |--------------------------------------------------------------------------
        */

        return [
            'success' => true,

            'is_teacher' =>
                !empty($teacherCourses),

            'courses' =>
                $teacherCourses,

            'roles' =>
                array_values(
                    array_unique(
                        array_keys(
                            $detectedRoles
                        )
                    )
                ),

            'role_names' =>
                array_values(
                    array_unique(
                        array_values(
                            $detectedRoles
                        )
                    )
                ),
        ];
    }


        /*
    |--------------------------------------------------------------------------
    | OBTENER CURSOS DEL PROFESOR
    |--------------------------------------------------------------------------
    |
    | Utiliza la misma validación que ya se usa durante el login.
    |
    */

    public function getTeacherCourses(
        string $token,
        int $userId
    ): array {

        $teacherResponse =
            $this->checkTeacher(
                $token,
                $userId
            );


        if (!$teacherResponse['success']) {

            return [
                'success' => false,

                'message' =>
                    $teacherResponse['message']
                    ?? 'No fue posible obtener los cursos.',

                'data' => [],
            ];

        }


        $courses =
            $teacherResponse['courses']
            ?? [];


        $resultado = [];


        foreach ($courses as $course) {

            if (empty($course['id'])) {
                continue;
            }


            $resultado[] = [

                'id' =>
                    (int) $course['id'],

                'nombre' =>
                    $course['name']
                    ?? 'Curso',

            ];

        }


        return [
            'success' => true,
            'data' => $resultado,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER GRUPOS DEL CURSO
    |--------------------------------------------------------------------------
    */

    public function getCourseGroups(
        string $token,
        int $courseId
    ): array {

        $response =
            $this->call(
                $token,
                'core_group_get_course_groups',
                [
                    'courseid' =>
                        $courseId,
                ]
            );


        if (!$response['success']) {

            return [
                'success' => false,

                'message' =>
                    $response['message']
                    ?? 'No fue posible obtener los grupos.',

                'data' => [],
            ];

        }


        $groups =
            $response['data']
            ?? [];


        $resultado = [];


        foreach ($groups as $group) {

            if (empty($group['id'])) {
                continue;
            }


            $resultado[] = [

                'id' =>
                    (int) $group['id'],

                'nombre' =>
                    $group['name']
                    ?? 'Grupo',

            ];

        }


        return [
            'success' => true,
            'data' => $resultado,
        ];
    }

/*
|--------------------------------------------------------------------------
| OBTENER USUARIOS DE UN GRUPO
|--------------------------------------------------------------------------
|
| Esta función devuelve los IDs de todos los usuarios
| que pertenecen al grupo seleccionado.
|
*/

public function getGroupUserIds(
    string $token,
    int $groupId
): array {

    /*
    |--------------------------------------------------------------------------
    | CONSULTAR MOODLE
    |--------------------------------------------------------------------------
    */

    $response =
        $this->call(
            $token,
            'core_group_get_group_members',
            [
                'groupids[0]' =>
                    $groupId,
            ]
        );


    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    if (!$response['success']) {

        return [
            'success' => false,

            'message' =>
                $response['message']
                ?? 'No fue posible obtener los integrantes del grupo.',

            'data' => [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | RESPUESTA DE MOODLE
    |--------------------------------------------------------------------------
    */

    $grupos =
        $response['data']
        ?? [];


    /*
    |--------------------------------------------------------------------------
    | BUSCAR EL GRUPO SOLICITADO
    |--------------------------------------------------------------------------
    */

    foreach ($grupos as $grupo) {

        if (
            (int) ($grupo['groupid'] ?? 0)
            !==
            $groupId
        ) {
            continue;
        }


        /*
         * Moodle devuelve los integrantes
         * dentro de userids.
         */
        $userIds =
            $grupo['userids']
            ?? [];


        /*
         * Convertir todos los IDs a enteros.
         */
        $userIds =
            array_map(
                function ($userId) {

                    return (int) $userId;

                },
                $userIds
            );


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return [
            'success' => true,

            'data' =>
                array_values(
                    array_unique(
                        $userIds
                    )
                ),
        ];
    }


    /*
     * Si el grupo existe pero no tiene usuarios.
     */
    return [
        'success' => true,

        'data' => [],
    ];
}


    /*
    |--------------------------------------------------------------------------
    | OBTENER EXÁMENES DEL CURSO
    |--------------------------------------------------------------------------
    */
public function getCourseQuizzes(
    string $token,
    int $courseId
): array {

    $response =
        $this->call(
            $token,
            'mod_quiz_get_quizzes_by_courses',
            [
                'courseids[0]' =>
                    $courseId,
            ]
        );


    if (!$response['success']) {

        return [
            'success' => false,

            'message' =>
                $response['message']
                ?? 'No fue posible obtener los exámenes.',

            'data' => [],
        ];
    }


    $quizzes =
        $response['data']['quizzes']
        ?? [];


    $resultado = [];


    foreach ($quizzes as $quiz) {

        if (empty($quiz['id'])) {
            continue;
        }


       $resultado[] = [

    'id' =>
        (int) $quiz['id'],

    'cmid' =>
        isset($quiz['coursemodule'])
            ? (int) $quiz['coursemodule']
            : 0,

    'nombre' =>
        $quiz['name']
        ?? 'Examen',

    'fecha_hora' =>
        isset($quiz['timeopen'])
            ? (int) $quiz['timeopen']
            : 0,

];
    }


    return [
        'success' => true,
        'data' => $resultado,
    ];
}

public function getQuizStudents(
    string $token,
    int $courseId,
    int $quizId
): array {

    /*
    |--------------------------------------------------------------------------
    | OBTENER PARTICIPANTES DEL CURSO
    |--------------------------------------------------------------------------
    */

    $usersResponse =
        $this->call(
            $token,
            'core_enrol_get_enrolled_users',
            [
                'courseid' => $courseId,
            ]
        );


    if (!$usersResponse['success']) {

        return [
            'success' => false,
            'message' =>
                $usersResponse['message']
                ?? 'No fue posible obtener los alumnos.',
            'data' => [],
        ];
    }


    $usuarios =
        $usersResponse['data']
        ?? [];


    $alumnosTotal = 0;

    $alumnosConIntento = 0;

    $alumnosConIntentoIds = [];


    /*
    |--------------------------------------------------------------------------
    | RECORRER ÚNICAMENTE ALUMNOS
    |--------------------------------------------------------------------------
    */

    foreach ($usuarios as $usuario) {

        $esAlumno = false;


        foreach (($usuario['roles'] ?? []) as $rol) {

            $shortname =
                strtolower(
                    trim(
                        $rol['shortname']
                        ?? ''
                    )
                );


            if ($shortname === 'student') {

                $esAlumno = true;

                break;
            }
        }


        if (!$esAlumno) {
            continue;
        }


        $alumnosTotal++;


        /*
        |--------------------------------------------------------------------------
        | VERIFICAR SI EL ALUMNO TIENE INTENTO
        |--------------------------------------------------------------------------
        */

        $attemptsResponse =
            $this->call(
                $token,
                'mod_quiz_get_user_attempts',
                [
                    'quizid' =>
                        $quizId,

                    'userid' =>
                        (int) $usuario['id'],

                    'status' =>
                        'all',

                    'includepreviews' =>
                        0,
                ]
            );


        if (!$attemptsResponse['success']) {
            continue;
        }


        $intentos =
            $attemptsResponse['data']['attempts']
            ?? [];

if (!empty($intentos)) {

    $alumnosConIntento++;

    $alumnosConIntentoIds[] =
        (int) $usuario['id'];

}

}


/*
|--------------------------------------------------------------------------
| RESPUESTA DE ALUMNOS
|--------------------------------------------------------------------------
*/
return [
    'success' => true,

    'data' => [

        'alumnos_total' =>
            $alumnosTotal,

        'alumnos_con_intento' =>
            $alumnosConIntento,

        'alumnos_ids' =>
            $alumnosConIntentoIds,

    ],
];
}


/*
|--------------------------------------------------------------------------
| OBTENER CAPTURAS DE PROCTORING
|--------------------------------------------------------------------------
*/

public function getProctoringCamshots(
    string $token,
    int $courseId,
    int $cmid,
    int $userId
): array {

    return $this->call(
        $token,
        'quizaccess_proctoring_get_camshots',
        [
            'courseid' =>
                $courseId,

            'quizid' =>
                $cmid,

            'userid' =>
                $userId,
        ]
    );
}


/*
|--------------------------------------------------------------------------
| FIN DE MOODLE SERVICE
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONTAR IMÁGENES DE PROCTORING DEL EXAMEN
|--------------------------------------------------------------------------
*/

public function countProctoringImages(
    string $token,
    int $courseId,
    int $cmid,
    array $userIds
): array {

    $imagenesUnicas = [];


    foreach ($userIds as $userId) {

        $userId =
            (int) $userId;


        if ($userId <= 0) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | OBTENER CAPTURAS DEL ALUMNO
        |--------------------------------------------------------------------------
        */

        $response =
            $this->getProctoringCamshots(
                $token,
                $courseId,
                $cmid,
                $userId
            );


        if (!$response['success']) {

            return [
                'success' => false,

                'message' =>
                    $response['message']
                    ?? 'No fue posible obtener las capturas de proctoring.',

                'data' => [
                    'imagenes' => 0,
                ],
            ];
        }


        $camshots =
            $response['data']['camshots']
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | CONTAR SOLO CAPTURAS DEL EXAMEN CORRECTO
        |--------------------------------------------------------------------------
        */

        foreach ($camshots as $camshot) {

            $camshotCourseId =
                (int) ($camshot['courseid'] ?? 0);

            $camshotQuizId =
                (int) ($camshot['quizid'] ?? 0);

            $camshotUserId =
                (int) ($camshot['userid'] ?? 0);

            $url =
                trim(
                    $camshot['webcampicture']
                    ?? ''
                );


            if (
                $camshotCourseId !== $courseId
                ||
                $camshotQuizId !== $cmid
                ||
                $camshotUserId !== $userId
                ||
                $url === ''
            ) {
                continue;
            }


            /*
             * Usamos la URL como llave para evitar
             * contar dos veces la misma captura.
             */
            $imagenesUnicas[$url] = true;
        }
    }


    return [
        'success' => true,

        'data' => [
            'imagenes' =>
                count($imagenesUnicas),
        ],
    ];
}

/*
|--------------------------------------------------------------------------
| OBTENER IMÁGENES DE PROCTORING
|--------------------------------------------------------------------------
|
| Devuelve las URLs de las capturas por bloques.
| Por defecto se obtienen 24 imágenes.
|
*/

public function getProctoringImages(
    string $token,
    int $courseId,
    int $cmid,
    array $userIds,
    int $offset = 0,
    int $limit = 24
): array {

    $imagenes = [];

    $imagenesVistas = [];

    $indiceUnico = 0;


    /*
    |--------------------------------------------------------------------------
    | RECORRER ALUMNOS CON INTENTO
    |--------------------------------------------------------------------------
    */

    foreach ($userIds as $userId) {

        $userId =
            (int) $userId;


        if ($userId <= 0) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTAR CAPTURAS DEL ALUMNO
        |--------------------------------------------------------------------------
        */

        $response =
            $this->getProctoringCamshots(
                $token,
                $courseId,
                $cmid,
                $userId
            );


        if (!$response['success']) {

            return [
                'success' => false,

                'message' =>
                    $response['message']
                    ?? 'No fue posible obtener las capturas.',

                'data' => [
                    'imagenes' => [],
                ],
            ];
        }


        $camshots =
            $response['data']['camshots']
            ?? [];


        /*
        |--------------------------------------------------------------------------
        | FILTRAR CAPTURAS
        |--------------------------------------------------------------------------
        */

        foreach ($camshots as $camshot) {

            $camshotCourseId =
                (int) (
                    $camshot['courseid']
                    ?? 0
                );


            $camshotQuizId =
                (int) (
                    $camshot['quizid']
                    ?? 0
                );


            $camshotUserId =
                (int) (
                    $camshot['userid']
                    ?? 0
                );


            $url =
                trim(
                    $camshot['webcampicture']
                    ?? ''
                );


            /*
             * Ignorar capturas que no sean
             * exactamente del examen seleccionado.
             */
            if (
                $camshotCourseId !== $courseId
                ||
                $camshotQuizId !== $cmid
                ||
                $camshotUserId !== $userId
                ||
                $url === ''
            ) {
                continue;
            }


            /*
             * Evitar imágenes duplicadas.
             */
            if (isset($imagenesVistas[$url])) {
                continue;
            }


            $imagenesVistas[$url] =
                true;


            /*
             * Saltar las imágenes anteriores
             * según la paginación.
             */
            if ($indiceUnico < $offset) {

                $indiceUnico++;

                continue;
            }


            /*
             * Agregar imagen.
             */
            $imagenes[] = [

                'url' =>
                    $url,

                'userid' =>
                    $camshotUserId,

                'fecha' =>
                    isset($camshot['timemodified'])
                        ? (int) $camshot['timemodified']
                        : 0,

            ];


            $indiceUnico++;


            /*
             * Cuando ya tenemos 24,
             * no seguimos recorriendo Moodle.
             */
            if (count($imagenes) >= $limit) {

                return [
                    'success' => true,

                    'data' => [

                        'imagenes' =>
                            $imagenes,

                        'offset' =>
                            $offset,

                        'limit' =>
                            $limit,

                    ],
                ];
            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RESPUESTA FINAL
    |--------------------------------------------------------------------------
    */

    return [
        'success' => true,

        'data' => [

            'imagenes' =>
                $imagenes,

            'offset' =>
                $offset,

            'limit' =>
                $limit,

        ],
    ];
}

}