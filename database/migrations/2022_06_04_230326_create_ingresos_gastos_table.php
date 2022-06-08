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
        Schema::create('ingresos_gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concepto_id');
            $table->decimal('monto',11,2);
            $table->string('observaciones',500);
            $table->char('tipo',1);
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos_gastos');
    }
};
