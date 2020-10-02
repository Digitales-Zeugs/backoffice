<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('works_registration');
            $table->foreignId('distribution_id')->nullable()->constrained('works_distribution');
            $table->foreignId('action_id')->constrained('works_logs_actions');
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
        Schema::dropIfExists('works_logs');
    }
}
