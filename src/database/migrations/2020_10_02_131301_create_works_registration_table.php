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

            $table->foreignId('member_id')->nullable()->constrained('members');
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->unsignedBigInteger('genre_id')->references('cod_int_gen')->on('source_genres');
            $table->string('duration', 5);
            
            $table->date('dnda_ed_date')->nullable();
            $table->string('audio_dnda_ed_file');
            $table->string('lyric_dnda_ed_file');
            
            $table->date('dnda_in_date')->nullable();
            $table->string('audio_dnda_in_file');
            $table->string('lyric_dnda_in_file');

            $table->string('lyric_text');
            
            $table->string('lyric_file')->nullable();
            $table->string('audio_file')->nullable();

            $table->boolean('submitted')->default(false);

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
