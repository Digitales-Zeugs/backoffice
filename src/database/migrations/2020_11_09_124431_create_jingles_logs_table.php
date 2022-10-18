<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJinglesLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jingles_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('jingles_registration');
            $table->foreignId('agreement_id')->nullable()->constrained('jingles_registration_agreements');
            $table->foreignId('action_id')->constrained('jingles_logs_actions');
            $table->dateTime('time');
            $table->json('action_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jingles_logs');
    }
}
