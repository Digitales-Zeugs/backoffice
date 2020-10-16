<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribution_id')->constrained('works_distribution');

            $table->string('address_country', 10)->references('tis_n')->on('source_countries')->nullable();
            $table->foreignId('address_state')->nullable()->constrained('states');
            $table->foreignId('address_city')->nullable()->constrained('cities');
            $table->string('address_zip', 10);
            $table->string('apartment', 20);
            $table->string('birth_country', 10)->references('tis_n')->on('source_countries')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('doc_type', 10)->references('code')->on('source_types')->nullable();
            $table->string('email', 254);
            $table->string('floor', 20);
            $table->string('name', 100);
            $table->string('phone_area', 10);
            $table->string('phone_country', 5);
            $table->string('phone_number', 20);
            $table->string('street_name', 100);
            $table->string('street_number', 20);

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
        Schema::dropIfExists('works_meta');
    }
}
