<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalisisHistorial extends Model
{
    protected $fillable = [
    'job_id',
    'moodle_username',
    'nombre_archivo',
    'nombre_carpeta',
    'fecha_analisis',
    'total_imagenes',
    'total_carpetas',
    'nivel_confianza',
    'porcentaje_confianza',
    'ruta_pdf',
];

protected $casts = [
    'fecha_analisis' => 'datetime',
];
}
