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

            $table->string('address_city');
            $table->string('address_country')->references('tis_n')->on('source_countries');
            $table->string('address_state');
            $table->string('address_zip', 10);
            $table->string('apartment');
            $table->string('birth_country')->references('tis_n')->on('source_countries');
            $table->date('birth_date');
            $table->string('doc_type', 10)->references('code')->on('source_types');
            $table->string('email', 254);
            $table->string('floor', 20);
            $table->string('name');
            $table->string('phone_area');
            $table->string('phone_country');
            $table->string('phone_number');
            $table->string('street_name', 100);
            $table->string('street_number');

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
