<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenciasDescargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('evidencias_descargas', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('moodle_user_id');

        $table->unsignedBigInteger('course_id')->nullable();
        $table->unsignedBigInteger('quiz_id')->nullable();
        $table->unsignedBigInteger('cmid')->nullable();

        $table->string('nombre_examen');

        $table->string('nombre_zip');
        $table->string('ruta_zip');

        $table->string('job_id')->unique();

        $table->string('estado')->default('pendiente');

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
        Schema::dropIfExists('evidencias_descargas');
    }
}
