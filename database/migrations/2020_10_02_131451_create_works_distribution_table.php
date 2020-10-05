<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works_distribution', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('works_registration');
            $table->string('function');
            $table->string('member');
            $table->string('name');
            $table->string('dni');
            $table->string('amount');
            $table->boolean('response')->nullable()->default(null);
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
        Schema::dropIfExists('works_distribution');
    }
}
