<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJinglesRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * applicant -> parts[type=applicant] -> people
     * advertiser -> parts[type=advertiser] -> people
     * agency -> parts[type=agency] ->people
     * producer -> parts[type=producer] ->people
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jingles_registration', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_special')->default(false);
            $table->unsignedBigInteger('request_action_id')->nullable();

            $table->unsignedTinyInteger('validity')->nullable();
            $table->date('air_date')->nullable();

            $table->json('ads_duration')->nullable();

            $table->unsignedBigInteger('broadcast_territory_id')->nullable();
            $table->json('territory_id')->nullable();

            $table->foreignId('media_id')->nullable()->constrained('jingles_registration_media');

            $table->boolean('subsection_i')->default(false);

            $table->unsignedTinyInteger('agency_type_id')->nullable();

            $table->string('product_brand')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_name')->nullable();

            $table->string('work_title')->nullable();
            $table->boolean('work_original')->default(false);
            $table->string('work_dnda')->nullable();
            $table->string('work_authors')->nullable();
            $table->string('work_composers')->nullable();
            $table->string('work_editors')->nullable();
            $table->boolean('work_script_mod')->default(false);
            $table->boolean('work_music_mod')->default(false);

            $table->boolean('authors_agreement')->default(false);
            $table->decimal('authors_tariff', 8, 2)->default('0.00');

            $table->unsignedTinyInteger('tariff_payer_id')->nullable();
            $table->string('tariff_representation')->nullable();

            $table->foreignId('status_id')->nullable()->constrained('jingles_registration_status');

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
        Schema::dropIfExists('jingles_registration');
    }
}
