<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_registration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->tinyInteger('status');
            $table->string('name');
            $table->date('birth_date');
            $table->string('birth_city_text', 50)->nullable();
            $table->foreignId('birth_city_id')->nullable()->constrained('cities');
            $table->string('birth_state_text', 50)->nullable();
            $table->foreignId('birth_state_id')->nullable()->constrained('states');
            $table->string('birth_country_id', 15)->references('idx')->on('source_countries');
            $table->string('doc_number', 50);
            $table->string('doc_country', 50);
            $table->string('work_code', 20);
            $table->string('address_street');
            $table->string('address_number', 20)->nullable();
            $table->string('address_floor', 10)->nullable();
            $table->string('address_apt', 10);
            $table->string('address_zip', 10);
            $table->string('address_city_text', 50)->nullable();
            $table->foreignId('address_city_id')->nullable()->constrained('cities');
            $table->string('address_state_text', 50)->nullable();
            $table->foreignId('address_state_id')->nullable()->constrained('states');
            $table->string('address_country_id', 15)->references('idx')->on('source_countries');
            $table->string('landline', 15)->nullable();
            $table->string('mobile', 15);
            $table->string('email', 254);
            $table->string('pseudonym');
            $table->string('band')->nullable();
            $table->string('entrance_work');
            $table->unsignedBigInteger('genre_id')->references('cod_int_gen')->on('source_genres');
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
        Schema::dropIfExists('members_registration');
    }
}
