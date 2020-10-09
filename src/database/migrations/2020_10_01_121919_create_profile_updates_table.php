<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_updates', function (Blueprint $table) {
            $table->id();
            $table->string('member_id', 10);
            $table->unsignedTinyInteger('heir');
            $table->string('email');
            $table->string('name');
            $table->string('address_type');
            $table->string('address', 500);
            $table->string('address_zip', 50);
            $table->string('address_city', 200);
            $table->string('address_state', 150);
            $table->string('address_country', 250);
            $table->unsignedInteger('phone_country');
            $table->unsignedInteger('phone_area');
            $table->unsignedBigInteger('phone_number');
            $table->unsignedInteger('cell_country');
            $table->unsignedInteger('cell_area');
            $table->unsignedBigInteger('cell_number');
            $table->timestamps();
            $table->foreignId('status_id')->constrained('profile_updates_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_updates');
    }
}
