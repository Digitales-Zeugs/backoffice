<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceSocietiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_societies', function (Blueprint $table) {
            $table->integer('cod_soc');
            $table->string('description', 50);
            $table->string('abv_soc', 20);
            $table->string('des_soc', 100);
            $table->string('logo_file_id', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_societies');
    }
}
