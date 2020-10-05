<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_members', function (Blueprint $table) {
            $table->unsignedBigInteger('cod_mem_sq');
            $table->string('codanita', 20);
            $table->string('ipname', 20);
            $table->string('nombre', 50);
            $table->string('domicilio', 200);
            $table->string('piso', 20);
            $table->string('dpto', 20);
            $table->string('cod_postal', 20);
            $table->string('dad_geo_id', 20);
            $table->string('dir_comments');
            $table->string('tipo_doc', 10);
            $table->string('num_doc', 20);
            $table->string('email', 254);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_members');
    }
}
