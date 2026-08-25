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
}