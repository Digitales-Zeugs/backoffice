<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_genres', function (Blueprint $table) {
            $table->unsignedBigInteger('cod_int_gen');
            $table->string('log_creat', 20);
            $table->string('log_updat', 20);
            $table->string('log_user', 50);
            $table->string('des_int_gen', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_genres');
    }
}
