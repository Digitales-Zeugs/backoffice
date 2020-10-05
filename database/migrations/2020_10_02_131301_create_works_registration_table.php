<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works_registration', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            
            $table->string('lyric_dnda_file');
            $table->date('lyric_dnda_date');
            $table->string('lyric_file')->nullable();
            $table->string('lyric_text');
            
            $table->string('audio_dnda_file');
            $table->date('audio_dnda_date');
            $table->string('audio_file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works_registration');
    }
}
