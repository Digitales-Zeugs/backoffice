<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntryDateToWorksRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /**
     * Consulta para setear la fecha de entrada de los datos preexistentes
     * UPDATE works_registration SET works_registration.entry_date = (SELECT time FROM works_logs WHERE works_logs.registration_id = works_registration.id AND (works_logs.action_id = 3 OR works_logs.action_id = 4) LIMIT 1);
     */
    public function up()
    {
        Schema::table('works_registration', function (Blueprint $table) {
            $table->date('entry_date')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works_registration', function (Blueprint $table) {
            $table->drop('entry_date');
        });
    }
}
