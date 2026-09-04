<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalisisHistorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analisis_historials', function (Blueprint $table) {

    $table->id();

    $table->string('job_id')->unique();

    $table->string('moodle_username')->nullable();

    $table->string('nombre_archivo');

    $table->string('nombre_carpeta');

    $table->dateTime('fecha_analisis');

    $table->unsignedInteger('total_imagenes')->default(0);

    $table->unsignedInteger('total_carpetas')->default(0);

    $table->string('nivel_confianza')->nullable();

    $table->unsignedTinyInteger('porcentaje_confianza')->nullable();

    $table->string('ruta_pdf');

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_historials');
    }

    
}
