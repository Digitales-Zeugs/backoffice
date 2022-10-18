<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDecimalsToWorksDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works_distribution', function (Blueprint $table) {
            $table->decimal('public')->change();
            $table->decimal('mechanic')->change();
            $table->decimal('sync')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works_distribution', function (Blueprint $table) {
            $table->tinyInteger('sync')->change();
            $table->tinyInteger('mechanic')->change();
            $table->tinyInteger('public')->change();
        });
    }
}
