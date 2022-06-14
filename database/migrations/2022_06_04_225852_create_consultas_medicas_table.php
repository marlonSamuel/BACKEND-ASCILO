<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_medicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitude_id');
            $table->unsignedBigInteger('medico_id');
            $table->string('diagnostico',1000)->nullable();
            $table->string('observaciones',500)->nullable();
            $table->datetime('fecha_asignada');
            $table->datetime('fecha_asignada_fin');
            $table->timestamps();

            $table->foreign('solicitude_id')->references('id')->on('solicitudes');
            $table->foreign('medico_id')->references('id')->on('medicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultas_medicas');
    }
};
