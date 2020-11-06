<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMembersRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members_registration', function (Blueprint $table) {
            $table->foreignId('status_id')->after('status')->constrained('members_registration_status');
            $table->dropColumn('status');
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
        Schema::table('members_registration', function (Blueprint $table) {
            $table->tinyInteger('status')->after('status_id');
            $table->dropColumn('status_id');
            $table->dropColumn('approved');
        });
    }
}
