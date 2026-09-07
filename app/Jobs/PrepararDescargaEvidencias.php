<?php

namespace App\Jobs;

use App\Services\MoodleService;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class PrepararDescargaEvidencias implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN DEL JOB
    |--------------------------------------------------------------------------
    */

    public $tries = 1;

    public $timeout = 7200;


    /*
    |--------------------------------------------------------------------------
    | DATOS DEL PROCESO
    |--------------------------------------------------------------------------
    */

    private $jobId;

    private $tokenCifrado;

    private $courseId;

    private $cmid;

    private $alumnosIds;

    private $usuariosAlumnos;

    private $nombreExamen;

    private $rutaZip;

    private $nombreZip;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        string $jobId,
        string $tokenCifrado,
        int $courseId,
        int $cmid,
        array $alumnosIds,
        array $usuariosAlumnos,
        string $nombreExamen,
        string $rutaZip,
        string $nombreZip
    ) {
        $this->jobId =
            $jobId;

        $this->tokenCifrado =
            $tokenCifrado;

        $this->courseId =
            $courseId;

        $this->cmid =
            $cmid;

        $this->alumnosIds =
            $alumnosIds;

        $this->usuariosAlumnos =
            $usuariosAlumnos;

        $this->nombreExamen =
            $nombreExamen;

        $this->rutaZip =
            $rutaZip;

        $this->nombreZip =
            $nombreZip;
    }


    /*
    |--------------------------------------------------------------------------
    | EJECUTAR JOB
    |--------------------------------------------------------------------------
    */

    public function handle(
        MoodleService $moodleService
    ) {
        @set_time_limit(0);


        try {

            /*
            |--------------------------------------------------------------------------
            | RECUPERAR TOKEN DE MOODLE
            |--------------------------------------------------------------------------
            */

            $token =
                decrypt(
                    $this->tokenCifrado
                );


            /*
            |--------------------------------------------------------------------------
            | ESTADO INICIAL
            |--------------------------------------------------------------------------
            */

            $this->guardarProgreso([
                'estado' =>
                    'procesando',

                'fase' =>
                    'Buscando evidencias...',

                'porcentaje' =>
                    0,

                'actual' =>
                    0,

                'total' =>
                    0,

                'archivo_actual' =>
                    '',
            ]);


            /*
            |--------------------------------------------------------------------------
            | OBTENER TODAS LAS EVIDENCIAS
            |--------------------------------------------------------------------------
            */

            $resultado =
                $moodleService
                    ->getProctoringImages(
                        $token,
                        $this->courseId,
                        $this->cmid,
                        $this->alumnosIds,
                        0,
                        PHP_INT_MAX
                    );


            if (
                !$resultado['success']
            ) {

                throw new \RuntimeException(
                    $resultado['message']
                    ??
                    'No fue posible obtener las evidencias.'
                );
            }


            $imagenes =
                $resultado['data']['imagenes']
                ?? [];


            if (
                empty(
                    $imagenes
                )
            ) {

                throw new \RuntimeException(
                    'El examen no contiene evidencias para descargar.'
                );
            }


            $totalImagenes =
                count(
                    $imagenes
                );


            /*
            |--------------------------------------------------------------------------
            | PREPARAR DIRECTORIO DEL ZIP
            |--------------------------------------------------------------------------
            */

            $directorioZip =
                dirname(
                    $this->rutaZip
                );


            if (
                !is_dir(
                    $directorioZip
                )
            ) {

                $creado =
                    mkdir(
                        $directorioZip,
                        0755,
                        true
                    );


                if (
                    !$creado
                    &&
                    !is_dir(
                        $directorioZip
                    )
                ) {

                    throw new \RuntimeException(
                        'No fue posible crear el directorio temporal.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CREAR ZIP
            |--------------------------------------------------------------------------
            */

            $zip =
                new \ZipArchive();


            $resultadoZip =
                $zip->open(
                    $this->rutaZip,
                    \ZipArchive::CREATE
                    |
                    \ZipArchive::OVERWRITE
                );


            if (
                $resultadoZip !== true
            ) {

                throw new \RuntimeException(
                    'No fue posible crear el archivo ZIP.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | PREPARAR IMÁGENES
            |--------------------------------------------------------------------------
            */

            $contadorAlumno =
                [];

            $items =
                [];

            $imagenesFallidas =
                0;


            foreach (
                $imagenes
                as $indice => $imagen
            ) {

                /*
                |--------------------------------------------------------------------------
                | URL
                |--------------------------------------------------------------------------
                */

                $url =
                    trim(
                        (string) (
                            $imagen['url']
                            ?? ''
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | ALUMNO
                |--------------------------------------------------------------------------
                */

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
                | CONTADOR POR ALUMNO
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
                | USERNAME DEL ALUMNO
                |--------------------------------------------------------------------------
                */

                $username =
                    trim(
                        (string) (
                            $this->usuariosAlumnos[
                                $alumnoId
                            ]
                            ?? ''
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | LIMPIAR USERNAME
                |--------------------------------------------------------------------------
                */

                $usernameSeguro =
                    preg_replace(
                        '/[^A-Za-z0-9._-]+/',
                        '_',
                        $username
                    );


                $usernameSeguro =
                    trim(
                        (string) $usernameSeguro,
                        '._-'
                    );


                /*
                |--------------------------------------------------------------------------
                | CARPETA DEL ALUMNO
                |--------------------------------------------------------------------------
                */

                $carpetaAlumno =
                    $usernameSeguro !== ''
                        ? $usernameSeguro
                        : 'alumno_' . $alumnoId;


                /*
                |--------------------------------------------------------------------------
                | NOMBRE BASE DE EVIDENCIA
                |--------------------------------------------------------------------------
                */

                $nombreBase =
                    sprintf(
                        '%s/evidencia_%04d',
                        $carpetaAlumno,
                        $contadorAlumno[
                            $alumnoId
                        ]
                    );


                /*
                |--------------------------------------------------------------------------
                | NOMBRE ORIGINAL
                |--------------------------------------------------------------------------
                |
                | Este nombre será el que mostremos
                | en la pantalla de progreso.
                |
                */

                $pathOriginal =
                    (string) parse_url(
                        $url,
                        PHP_URL_PATH
                    );


                $nombreOriginal =
                    urldecode(
                        basename(
                            $pathOriginal
                        )
                    );


                if (
                    $nombreOriginal === ''
                ) {

                    $nombreOriginal =
                        'evidencia_'
                        .
                        (
                            $indice + 1
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | CONVERTIR URL A WEBSERVICE
                |--------------------------------------------------------------------------
                */

                $urlWebService =
                    $this
                        ->crearUrlWebService(
                            $url,
                            $token
                        );


                if (
                    !$urlWebService
                ) {

                    $imagenesFallidas++;

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | GUARDAR ITEM
                |--------------------------------------------------------------------------
                */

                $items[
                    $indice
                ] = [

                    'url' =>
                        $urlWebService,

                    'nombre_original' =>
                        $nombreOriginal,

                    'nombre_base' =>
                        $nombreBase,

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | CONFIGURAR CLIENTE HTTP
            |--------------------------------------------------------------------------
            */

            $verifySsl =
                config(
                    'moodle.verify_ssl'
                );


            if (
                $verifySsl === null
            ) {

                $verifySsl =
                    true;
            }


            $cliente =
                new Client([

                    'timeout' =>
                        30,

                    'connect_timeout' =>
                        10,

                    'verify' =>
                        $verifySsl,

                    /*
                     * Los códigos 4xx/5xx se manejan
                     * manualmente en fulfilled.
                     */
                    'http_errors' =>
                        false,

                ]);


            /*
            |--------------------------------------------------------------------------
            | CONTADORES
            |--------------------------------------------------------------------------
            */

            $procesadas =
                $imagenesFallidas;

            $descargadas =
                0;

            $ultimaActualizacion =
                microtime(
                    true
                );

            $ultimoArchivo =
                '';


            /*
            |--------------------------------------------------------------------------
            | ESTADO: DESCARGANDO
            |--------------------------------------------------------------------------
            */

            $this->guardarProgreso([

                'estado' =>
                    'procesando',

                'fase' =>
                    'Descargando imágenes...',

                'porcentaje' =>
                    0,

                'actual' =>
                    $procesadas,

                'total' =>
                    $totalImagenes,

                'archivo_actual' =>
                    '',

            ]);


            /*
            |--------------------------------------------------------------------------
            | GENERADOR DE PETICIONES
            |--------------------------------------------------------------------------
            */

            $peticiones =
                function () use (
                    $cliente,
                    $items
                ) {

                    foreach (
                        $items
                        as $indice => $item
                    ) {

                        yield $indice =>
                            function () use (
                                $cliente,
                                $item
                            ) {

                                return $cliente
                                    ->getAsync(
                                        $item['url']
                                    );
                            };
                    }
                };


            /*
            |--------------------------------------------------------------------------
            | DESCARGAS CONCURRENTES
            |--------------------------------------------------------------------------
            |
            | Se descargan hasta 8 imágenes
            | de Moodle simultáneamente.
            |
            */

            $pool =
                new Pool(
                    $cliente,
                    $peticiones(),
                    [

                        'concurrency' =>
                            8,


                        /*
                        |--------------------------------------------------------------------------
                        | PETICIÓN TERMINADA
                        |--------------------------------------------------------------------------
                        */

                        'fulfilled' =>
                            function (
                                $response,
                                $indice
                            ) use (
                                &$zip,
                                &$items,
                                &$procesadas,
                                &$descargadas,
                                &$imagenesFallidas,
                                &$ultimaActualizacion,
                                &$ultimoArchivo,
                                $totalImagenes
                            ) {

                                $item =
                                    $items[
                                        $indice
                                    ];


                                /*
                                |--------------------------------------------------------------------------
                                | NOMBRE ACTUAL
                                |--------------------------------------------------------------------------
                                */

                                $ultimoArchivo =
                                    $item[
                                        'nombre_original'
                                    ];


                                /*
                                |--------------------------------------------------------------------------
                                | STATUS HTTP
                                |--------------------------------------------------------------------------
                                */

                                $status =
                                    $response
                                        ->getStatusCode();


                                /*
                                |--------------------------------------------------------------------------
                                | CONTENT TYPE
                                |--------------------------------------------------------------------------
                                */

                                $contentType =
                                    strtolower(
                                        trim(
                                            explode(
                                                ';',
                                                $response
                                                    ->getHeaderLine(
                                                        'Content-Type'
                                                    )
                                            )[0]
                                            ?? ''
                                        )
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | VALIDAR RESPUESTA
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    $status < 200
                                    ||
                                    $status >= 300
                                    ||
                                    strpos(
                                        $contentType,
                                        'image/'
                                    ) !== 0
                                ) {

                                    $imagenesFallidas++;

                                    $procesadas++;


                                    $this
                                        ->actualizarProgresoImagenes(
                                            $procesadas,
                                            $totalImagenes,
                                            $ultimoArchivo,
                                            $ultimaActualizacion
                                        );


                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | EXTENSIÓN
                                |--------------------------------------------------------------------------
                                */

                                $extension =
                                    $this
                                        ->extensionDesdeContentType(
                                            $contentType
                                        );


                                /*
                                |--------------------------------------------------------------------------
                                | RUTA DENTRO DEL ZIP
                                |--------------------------------------------------------------------------
                                */

                                $rutaDentroZip =
                                    $item[
                                        'nombre_base'
                                    ]
                                    .
                                    '.'
                                    .
                                    $extension;


                                /*
                                |--------------------------------------------------------------------------
                                | AGREGAR IMAGEN AL ZIP
                                |--------------------------------------------------------------------------
                                */

                                $agregada =
                                    $zip
                                        ->addFromString(
                                            $rutaDentroZip,
                                            (string)
                                            $response
                                                ->getBody()
                                        );


                                if (
                                    $agregada
                                ) {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | NO RECOMPRIMIR IMÁGENES
                                    |--------------------------------------------------------------------------
                                    |
                                    | PNG, JPG y WebP ya vienen comprimidas.
                                    | Guardarlas directamente reduce trabajo
                                    | de CPU al generar el ZIP.
                                    |
                                    */

                                    if (
                                        method_exists(
                                            $zip,
                                            'setCompressionName'
                                        )
                                    ) {

                                        @$zip
                                            ->setCompressionName(
                                                $rutaDentroZip,
                                                \ZipArchive::CM_STORE
                                            );
                                    }


                                    $descargadas++;

                                } else {

                                    $imagenesFallidas++;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | ACTUALIZAR CONTADOR
                                |--------------------------------------------------------------------------
                                */

                                $procesadas++;


                                $this
                                    ->actualizarProgresoImagenes(
                                        $procesadas,
                                        $totalImagenes,
                                        $ultimoArchivo,
                                        $ultimaActualizacion
                                    );
                            },


                        /*
                        |--------------------------------------------------------------------------
                        | ERROR DE RED
                        |--------------------------------------------------------------------------
                        */

                        'rejected' =>
                            function (
                                $reason,
                                $indice
                            ) use (
                                &$items,
                                &$procesadas,
                                &$imagenesFallidas,
                                &$ultimaActualizacion,
                                &$ultimoArchivo,
                                $totalImagenes
                            ) {

                                $ultimoArchivo =
                                    $items[
                                        $indice
                                    ][
                                        'nombre_original'
                                    ]
                                    ?? 'Evidencia';


                                $imagenesFallidas++;

                                $procesadas++;


                                $this
                                    ->actualizarProgresoImagenes(
                                        $procesadas,
                                        $totalImagenes,
                                        $ultimoArchivo,
                                        $ultimaActualizacion
                                    );
                            },
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | ESPERAR A QUE FINALICEN LAS DESCARGAS
            |--------------------------------------------------------------------------
            */

            $pool
                ->promise()
                ->wait();


            /*
            |--------------------------------------------------------------------------
            | GENERANDO ZIP
            |--------------------------------------------------------------------------
            */

            $this->guardarProgreso([

                'estado' =>
                    'procesando',

                'fase' =>
                    'Generando archivo ZIP...',

                'porcentaje' =>
                    95,

                'actual' =>
                    $totalImagenes,

                'total' =>
                    $totalImagenes,

                'archivo_actual' =>
                    $this->nombreZip,

            ]);


            /*
            |--------------------------------------------------------------------------
            | CREAR RESUMEN
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
                $this->nombreExamen
                .
                PHP_EOL
                .
                "Alumnos procesados: "
                .
                count(
                    $contadorAlumno
                )
                .
                PHP_EOL
                .
                "Evidencias encontradas: "
                .
                $totalImagenes
                .
                PHP_EOL
                .
                "Evidencias descargadas: "
                .
                $descargadas
                .
                PHP_EOL
                .
                "Evidencias no descargadas: "
                .
                $imagenesFallidas
                .
                PHP_EOL;


            $zip
                ->addFromString(
                    '_resumen_descarga.txt',
                    $resumen
                );


            /*
            |--------------------------------------------------------------------------
            | FINALIZAR ZIP
            |--------------------------------------------------------------------------
            */

            $this->guardarProgreso([

                'estado' =>
                    'procesando',

                'fase' =>
                    'Finalizando archivo ZIP...',

                'porcentaje' =>
                    98,

                'actual' =>
                    $totalImagenes,

                'total' =>
                    $totalImagenes,

                'archivo_actual' =>
                    $this->nombreZip,

            ]);


            /*
            |--------------------------------------------------------------------------
            | CERRAR ZIP
            |--------------------------------------------------------------------------
            */

            $zip->close();


            /*
            |--------------------------------------------------------------------------
            | VALIDAR RESULTADO
            |--------------------------------------------------------------------------
            */

            if (
                $descargadas <= 0
            ) {

                if (
                    is_file(
                        $this->rutaZip
                    )
                ) {

                    @unlink(
                        $this->rutaZip
                    );
                }


                throw new \RuntimeException(
                    'No fue posible descargar ninguna evidencia.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DESCARGA PREPARADA
            |--------------------------------------------------------------------------
            */

            $this->guardarProgreso([

                'estado' =>
                    'completado',

                'fase' =>
                    'Descarga preparada',

                'porcentaje' =>
                    100,

                'actual' =>
                    $totalImagenes,

                'total' =>
                    $totalImagenes,

                'archivo_actual' =>
                    $this->nombreZip,

                'nombre_zip' =>
                    $this->nombreZip,

                'ruta_zip' =>
                    $this->rutaZip,

                'imagenes_descargadas' =>
                    $descargadas,

                'imagenes_fallidas' =>
                    $imagenesFallidas,

                'alumnos' =>
                    count(
                        $contadorAlumno
                    ),

            ]);


        } catch (\Throwable $error) {

            /*
            |--------------------------------------------------------------------------
            | ELIMINAR ZIP INCOMPLETO
            |--------------------------------------------------------------------------
            */

            if (
                is_file(
                    $this->rutaZip
                )
            ) {

                @unlink(
                    $this->rutaZip
                );
            }


            /*
            |--------------------------------------------------------------------------
            | GUARDAR ERROR
            |--------------------------------------------------------------------------
            */

            $this->guardarProgreso([

                'estado' =>
                    'error',

                'fase' =>
                    'Error durante la descarga',

                'porcentaje' =>
                    0,

                'actual' =>
                    0,

                'total' =>
                    0,

                'archivo_actual' =>
                    '',

                'mensaje' =>
                    $error
                        ->getMessage(),

            ]);


            throw $error;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR PROGRESO DE IMÁGENES
    |--------------------------------------------------------------------------
    */

    private function actualizarProgresoImagenes(
        int $procesadas,
        int $total,
        string $archivo,
        float &$ultimaActualizacion
    ): void {

        /*
         * Evitamos escribir al caché
         * después de cada imagen.
         *
         * Aproximadamente cuatro
         * actualizaciones por segundo.
         */

        $ahora =
            microtime(
                true
            );


        if (
            $procesadas < $total
            &&
            (
                $ahora
                -
                $ultimaActualizacion
            ) < 0.25
        ) {

            return;
        }


        $ultimaActualizacion =
            $ahora;


        /*
        |--------------------------------------------------------------------------
        | PORCENTAJE
        |--------------------------------------------------------------------------
        |
        | Del 0 al 90 % corresponde
        | a la descarga de imágenes.
        |
        | 95 % = Generando ZIP
        | 98 % = Finalizando ZIP
        | 100 % = Terminado
        |
        */

        $porcentaje =
            (int) floor(
                (
                    $procesadas
                    /
                    max(
                        1,
                        $total
                    )
                )
                *
                90
            );


        $porcentaje =
            max(
                0,
                min(
                    90,
                    $porcentaje
                )
            );


        /*
        |--------------------------------------------------------------------------
        | GUARDAR ESTADO
        |--------------------------------------------------------------------------
        */

        $this->guardarProgreso([

            'estado' =>
                'procesando',

            'fase' =>
                'Descargando imágenes...',

            'porcentaje' =>
                $porcentaje,

            'actual' =>
                $procesadas,

            'total' =>
                $total,

            'archivo_actual' =>
                $archivo,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR URL DE WEBSERVICE DE MOODLE
    |--------------------------------------------------------------------------
    */

    private function crearUrlWebService(
        string $url,
        string $token
    ): ?string {

        /*
         * Moodle devuelve:
         *
         * /pluginfile.php/
         *
         * Para usar el token necesitamos:
         *
         * /webservice/pluginfile.php/
         */

        $urlWebService =
            preg_replace(
                '#/pluginfile\.php/#',
                '/webservice/pluginfile.php/',
                $url,
                1
            );


        if (
            !$urlWebService
        ) {

            return null;
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


        return $urlWebService
            .
            $separador
            .
            'token='
            .
            urlencode(
                $token
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DETECTAR EXTENSIÓN
    |--------------------------------------------------------------------------
    */

    private function extensionDesdeContentType(
        string $contentType
    ): string {

        if (
            strpos(
                $contentType,
                'image/png'
            ) !== false
        ) {

            return 'png';
        }


        if (
            strpos(
                $contentType,
                'image/webp'
            ) !== false
        ) {

            return 'webp';
        }


        if (
            strpos(
                $contentType,
                'image/bmp'
            ) !== false
        ) {

            return 'bmp';
        }


        if (
            strpos(
                $contentType,
                'image/gif'
            ) !== false
        ) {

            return 'gif';
        }


        /*
         * JPG/JPEG por defecto.
         */

        return 'jpg';
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR PROGRESO
    |--------------------------------------------------------------------------
    */

    private function guardarProgreso(
        array $datos
    ): void {

        Cache::put(
            'sgae_download_progress_'
            .
            $this->jobId,
            $datos,
            now()->addHours(
                2
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ERROR DEFINITIVO DEL JOB
    |--------------------------------------------------------------------------
    */

    public function failed(
        \Throwable $exception
    ): void {

        $this->guardarProgreso([

            'estado' =>
                'error',

            'fase' =>
                'Error durante la descarga',

            'porcentaje' =>
                0,

            'actual' =>
                0,

            'total' =>
                0,

            'archivo_actual' =>
                '',

            'mensaje' =>
                $exception
                    ->getMessage(),

        ]);
    }
}