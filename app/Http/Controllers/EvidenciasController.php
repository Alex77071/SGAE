<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class EvidenciasController extends Controller
{
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
        | VARIABLES PARA EL SCRIPT PYTHON
        |--------------------------------------------------------------------------
        */

        $process->setEnv([
            'MOODLE_DB_HOST' =>
                env('MOODLE_DB_HOST'),

            'MOODLE_DB_USER' =>
                env('MOODLE_DB_USER'),

            'MOODLE_DB_PASSWORD' =>
                env('MOODLE_DB_PASSWORD'),

            'MOODLE_DB_DATABASE' =>
                env('MOODLE_DB_DATABASE'),

            'MOODLEDATA_FILEDIR' =>
                env('MOODLEDATA_FILEDIR'),

            'MOODLE_OUTPUT_DIR' =>
                env('MOODLE_OUTPUT_DIR'),
        ]);


        /*
        |--------------------------------------------------------------------------
        | TIEMPO MÁXIMO
        |--------------------------------------------------------------------------
        |
        | 300 segundos = 5 minutos.
        |
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
                    $process->getErrorOutput()
                    ?: 'Error ejecutando Python.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | LEER RESPUESTA JSON
        |--------------------------------------------------------------------------
        */

        $data = json_decode(
            trim(
                $process->getOutput()
            ),
            true
        );


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA INVÁLIDA
        |--------------------------------------------------------------------------
        */

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

        return response()->json(
            $this->ejecutarPython([
                'cursos',
            ])
        );
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


        return response()->json(
            $this->ejecutarPython([
                'grupos',

                (string) $request->courseid,
            ])
        );
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

            'groupid' =>
                'nullable|integer',
        ]);


        return response()->json(
            $this->ejecutarPython([
                'examenes',

                (string) $request->courseid,

                $request->groupid
                    ? (string) $request->groupid
                    : 'null',
            ])
        );
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
        | VALIDAR SELECCIÓN
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
        | EJECUTAR PYTHON
        |--------------------------------------------------------------------------
        */

        $resultado =
            $this->ejecutarPython([
                'descargar',

                (string) $request->courseid,

                (string) $request->quizid,

                $request->groupid
                    ? (string) $request->groupid
                    : 'null',
            ]);


        /*
        |--------------------------------------------------------------------------
        | ERROR DEL SCRIPT
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
        | RUTA DEL ZIP
        |--------------------------------------------------------------------------
        */

        $zip =
            $resultado['zip']
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | VALIDAR ZIP
        |--------------------------------------------------------------------------
        */

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

        return response()->download(
            $zip
        );
    }
}