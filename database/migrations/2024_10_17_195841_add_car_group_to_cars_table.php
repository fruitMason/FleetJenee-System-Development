<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarGroupToCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            //$table->enum('car_group', ['pool', 'assigned'])->default('pool');
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'car_group')) {
                $table->dropColumn('car_group');
            }
        });
    }

}
