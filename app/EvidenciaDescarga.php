<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvidenciaDescarga extends Model
{
    protected $table = 'evidencias_descargas';

    protected $fillable = [
        'moodle_user_id',
        'course_id',
        'quiz_id',
        'cmid',
        'nombre_examen',
        'nombre_zip',
        'ruta_zip',
        'job_id',
        'estado',
    ];

    public function getConnectionName()
    {
        return config(
            'database.evidencias_connection',
            'queue_sqlite'
        );
    }
}