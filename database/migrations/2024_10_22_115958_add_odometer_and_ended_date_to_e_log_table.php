<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOdometerAndEndedDateToELogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('e_log', function (Blueprint $table) {
            $table->integer('start_odometer')->nullable(); // or unsignedInteger if you prefer
            $table->integer('end_odometer')->nullable();
            $table->dateTime('ended_date')->nullable(); // use timestamp() if you prefer
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('e_log', function (Blueprint $table) {
            $table->dropColumn(['start_odometer', 'end_odometer', 'ended_date']);
        });
    }
}
