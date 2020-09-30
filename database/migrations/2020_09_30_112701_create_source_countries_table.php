<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_countries', function (Blueprint $table) {
            $table->string('tis_n', 10);
            $table->string('cod_tis_alfa', 5);
            $table->char('iso_lang', 2);
            $table->string('log_creat', 20);
            $table->string('log_updat', 20);
            $table->string('log_user', 50);
            $table->string('name_ter', 50);
            $table->string('abbrev_name_ter', 100);
            $table->string('offi_name_ter', 100);
            $table->string('unoffi_name_ter', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_countries');
    }
}
