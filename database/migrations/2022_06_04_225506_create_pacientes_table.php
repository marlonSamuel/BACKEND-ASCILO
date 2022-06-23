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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('cui',15);
            $table->unsignedBigInteger('psicopatologia_id');
            $table->string('primer_nombre',25);
            $table->string('segundo_nombre',25)->nullable();
            $table->string('tercer_nombre',25)->nullable();
            $table->string('primer_apellido',25);
            $table->string('segundo_apellido',25)->nullable();
            $table->char('genero',1);
            $table->date('fecha_nacimiento');
            $table->string('cui_responsable',15);
            $table->string('primer_nombre_responsable',25);
            $table->string('segundo_nombre_responsable',25)->nullable();
            $table->string('tercer_nombre_responsable',25)->nullable();
            $table->string('primer_apellido_responsable',25);
            $table->string('segundo_apellido_responsable',25)->nullable();
            $table->char('parentesco',1);
            $table->string('celular',15);
            $table->string('telefono',15)->nullable();
            $table->string('email',25)->unique();
            $table->string('direccion',150);
            $table->date('fecha_ingreso');
            $table->string('razon',250);
            $table->string('alergias',100)->nullable();
            $table->timestamps();

            $table->foreign('psicopatologia_id')->references('id')->on('psicopatologias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
};
