<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members_profiles');
    }
}
