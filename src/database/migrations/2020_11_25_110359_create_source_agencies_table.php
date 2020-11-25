<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_agencies', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cuit');
            $table->string('tipo', 1)->nullable();
            $table->integer('div_geo');
            $table->string('div_geo_desc');
            $table->integer('cod_pais')->nullable();
            $table->string('cod_pais_desc')->nullable();
            $table->string('direccion')->nullable();
            $table->string('cod_postal', 10)->nullable();
            $table->string('tel_pais', 10)->nullable();
            $table->string('tel_area', 10)->nullable();
            $table->string('tel_numero', 30)->nullable();
            $table->string('email', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_agencies');
    }
}
