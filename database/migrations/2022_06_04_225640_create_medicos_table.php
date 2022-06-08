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
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->string('cui',15);
            $table->unsignedBigInteger('especialidade_id');
            $table->string('primer_nombre',25);
            $table->string('segundo_nombre',25)->nullable();
            $table->string('tercer_nombre',25)->nullable();
            $table->string('primer_apelllido',25);
            $table->string('segundo_apellido',25)->nullable();
            $table->date('fecha_nacimiento');
            $table->string('telefono',15);
            $table->string('email',50)->unique();
            $table->string('direccion',250);

            $table->timestamps();

            $table->foreign('especialidade_id')->references('id')->on('especialidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicos');
    }
};
