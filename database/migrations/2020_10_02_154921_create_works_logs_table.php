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
            $table->foreignId('disitribution_id')->nullable()->constraint('works_distribution');
            $table->foreignId('action_id')->constraint('works_logs_actions');
            $table->json('action_data');
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
