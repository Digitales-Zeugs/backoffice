<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJinglesPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jingles_parts', function (Blueprint $table) {
            $table->foreignId('registration_id')->constrained('jingles_registration');
            $table->foreignId('person_id')->constrained('jingles_people');
            $table->enum('type', ['applicant', 'advertiser', 'agency', 'producer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jingles_parts');
    }
}
