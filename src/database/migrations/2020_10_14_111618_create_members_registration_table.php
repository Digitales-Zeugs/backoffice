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
            $table->string('name');
            $table->date('birth_date');
            $table->string('birth_city', 50);
            $table->string('birth_state', 50);
            $table->string('birth_country_id', 10)->references('tis_n')->on('source_countries');
            $table->string('doc_number', 50);
            $table->string('doc_country', 50);
            $table->string('work_code', 20);
            $table->string('address_street');
            $table->string('address_number', 20)->nullable();
            $table->string('address_floor', 10)->nullable();
            $table->string('address_apt', 10);
            $table->string('address_city', 50);
            $table->string('address_zip', 10);
            $table->string('address_state', 50);
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
