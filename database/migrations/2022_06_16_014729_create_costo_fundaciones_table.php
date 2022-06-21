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
        Schema::create('costo_fundaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consultas_medica_id');
            $table->decimal('consulta',11,2)->default(0);
            $table->decimal('medicamentos',11,2)->default(0);
            $table->decimal('examenes',11,2)->default(0);
            $table->decimal('total',11,2)->default(0);
            $table->boolean('pagado')->default(0);
            $table->timestamps();

            $table->foreign('consultas_medica_id')->references('id')->on('consultas_medicas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costo_fundaciones');
    }
};
