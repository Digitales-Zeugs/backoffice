<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJinglesRegistrationAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jingles_registration_agreements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('type_id');
            $table->string('member_idx', 50)->references('idx')->on('source_members')->nullable();

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
        Schema::dropIfExists('jingles_registration_agreements');
    }
}
