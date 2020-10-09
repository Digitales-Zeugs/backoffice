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

            $table->enum('type', ['member', 'no-member']);

            $table->string('fn', 5);

            $table->string('member_id', 20)->references('codanita')->on('source_members');
            $table->string('doc_number', 20)->references('num_doc')->on('source_members');

            $table->tinyInteger('public');
            $table->tinyInteger('mechanic');
            $table->tinyInteger('sync');

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
