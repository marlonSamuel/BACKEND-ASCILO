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
        Schema::create('consultas_medicas_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consultas_medica_id');
            $table->unsignedBigInteger('medicamento_id');
            $table->decimal('precio',11,2);
            $table->integer('cantidad');
            $table->integer('tiempo_aplicacion');
            $table->string('indicaciones',500);
            $table->boolean('entregado')->default(false);
            $table->timestamps();

            $table->foreign('consultas_medica_id')->references('id')->on('consultas_medicas');
            $table->foreign('medicamento_id')->references('id')->on('medicamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultas_medicas_medicamentos');
    }
};
