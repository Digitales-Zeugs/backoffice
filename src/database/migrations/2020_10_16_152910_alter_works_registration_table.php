<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterWorksRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works_registration', function (Blueprint $table) {
            $table->string('dnda_title')->after('title')->default('');
            $table->foreignId('status_id')->after('submitted')->nullable()->constrained('works_status');
            $table->mediumText('observations')->nullable();
            $table->dropColumn('submitted');
            $table->boolean('approved')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->boolean('submitted')->after('status_id')->default(false);
        $table->dropColumn('observations');
        $table->dropColumn('status_id');
        $table->dropColumn('dnda_title');
        $table->dropColumn('approved');
    }
}
