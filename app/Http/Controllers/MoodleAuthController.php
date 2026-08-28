<?php

namespace App\Http\Controllers;

use App\Services\MoodleService;
use Illuminate\Http\Request;

class MoodleAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INICIAR SESIÓN CON MOODLE
    |--------------------------------------------------------------------------
    */

    public function login(
        Request $request,
        MoodleService $moodle
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDAR FORMULARIO
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'usuario' => [
                'required',
                'string',
                'max:100',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | RECIBIR DATOS
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        | El input del formulario se llama "usuario".
        |
        */

        $username = trim(
            $request->input('usuario')
        );

        $password = $request->input('password');


        /*
        |--------------------------------------------------------------------------
        | 1. AUTENTICAR CONTRA MOODLE
        |--------------------------------------------------------------------------
        */

        $authentication = $moodle->authenticate(
            $username,
            $password
        );


        /*
        |--------------------------------------------------------------------------
        | ERROR DE AUTENTICACIÓN
        |--------------------------------------------------------------------------
        */

        if (!$authentication['success']) {

            $errorCode =
                $authentication['errorcode']
                ?? null;

            $message =
                $authentication['message']
                ?? 'Usuario o contraseña incorrectos.';


            return back()
                ->withInput(
                    $request->only('usuario')
                )
                ->with(
                    'login_error',
                    $message
                    . (
                        $errorCode
                            ? ' Código Moodle: ' . $errorCode
                            : ''
                    )
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TOKEN
        |--------------------------------------------------------------------------
        */

        $token = $authentication['token'];


        /*
        |--------------------------------------------------------------------------
        | 2. OBTENER INFORMACIÓN DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $userResponse = $moodle->getUserInfo(
            $token
        );


        if (!$userResponse['success']) {

            return back()
                ->withInput(
                    $request->only('usuario')
                )
                ->with(
                    'login_error',
                    'No fue posible obtener la información de tu cuenta de Moodle.'
                );
        }


        $user = $userResponse['data'];


        /*
        |--------------------------------------------------------------------------
        | OBTENER ID DEL USUARIO
        |--------------------------------------------------------------------------
        */

        $userId = (int) (
            $user['userid']
            ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDAR ID
        |--------------------------------------------------------------------------
        */

        if ($userId <= 0) {

            return back()
                ->withInput(
                    $request->only('usuario')
                )
                ->with(
                    'login_error',
                    'Moodle no devolvió un usuario válido.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | 3. COMPROBAR SI ES PROFESOR
        |--------------------------------------------------------------------------
        */

        $teacherResponse = $moodle->checkTeacher(
            $token,
            $userId
        );


        /*
        |--------------------------------------------------------------------------
        | ERROR CONSULTANDO PERMISOS
        |--------------------------------------------------------------------------
        */

        if (!$teacherResponse['success']) {

            return back()
                ->withInput(
                    $request->only('usuario')
                )
                ->with(
                    'login_error',
                    'No fue posible verificar tus permisos en Moodle.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | 4. USUARIO VÁLIDO PERO NO ES PROFESOR
        |--------------------------------------------------------------------------
        */

        if (!$teacherResponse['is_teacher']) {

            $roles =
                $teacherResponse['role_names']
                ?? [];


            $detail =
                !empty($roles)
                    ? ' Roles detectados: '
                        . implode(', ', $roles)
                        . '.'
                    : ' No se detectó un rol de profesor en tus cursos.';


            return back()
                ->withInput(
                    $request->only('usuario')
                )
                ->with(
                    'login_error',
                    'Tu cuenta de Moodle es válida, pero no tienes permiso para acceder a SGAE.'
                    . $detail
                );
        }



        /*
        |--------------------------------------------------------------------------
        | REGENERAR SESIÓN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | GUARDAR DATOS EN SESIÓN
        |--------------------------------------------------------------------------
        */

        $request->session()->put([
            'moodle_authenticated' => true,

            'moodle_user_id' =>
                $userId,

            'moodle_username' =>
                $user['username']
                ?? $username,

            'moodle_fullname' =>
                $user['fullname']
                ?? $username,

            'moodle_token' =>
                $token,

            'moodle_teacher_courses' =>
                $teacherResponse['courses']
                ?? [],

            'moodle_roles' =>
                $teacherResponse['roles']
                ?? [],
        ]);



        /*
|--------------------------------------------------------------------------
| 6. VERIFICAR TÉRMINOS Y CONDICIONES
|--------------------------------------------------------------------------
*/

/*
 * Recuperar el ID del profesor que
 * acabamos de guardar en sesión.
 */
$userId = $request->session()->get(
    'moodle_user_id'
);


if (!$userId) {

    return redirect()->route('login');

}


/*
 * Crear el nombre de cookie correspondiente
 * a este profesor.
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


/*
 * Buscar si ya aceptó los términos.
 */
$termsAccepted =
    $request->cookie(
        $termsCookieName
    );


/*
|--------------------------------------------------------------------------
| NO LOS HA ACEPTADO
|--------------------------------------------------------------------------
*/

if ($termsAccepted !== '1.0') {

    return redirect()->route(
        'legal.terminos'
    );

}


/*
|--------------------------------------------------------------------------
| YA LOS ACEPTÓ
|--------------------------------------------------------------------------
*/

return redirect()->route('inicio');


        /*
        |--------------------------------------------------------------------------
        | 6. IR A LA PANTALLA PRINCIPAL
        |--------------------------------------------------------------------------
        */

        return redirect()->route('inicio');
    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR SESIÓN
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | ELIMINAR DATOS DE MOODLE
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([
            'moodle_authenticated',
            'moodle_user_id',
            'moodle_username',
            'moodle_fullname',
            'moodle_token',
            'moodle_teacher_courses',
            'moodle_roles',
        ]);


        /*
        |--------------------------------------------------------------------------
        | INVALIDAR SESIÓN
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | REGRESAR AL LOGIN
        |--------------------------------------------------------------------------
        */

        return redirect()->route('login');
    }
}