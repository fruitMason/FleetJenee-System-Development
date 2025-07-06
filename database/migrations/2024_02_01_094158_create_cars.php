<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model')->index();
            $table->string('year', 10)->nullable()->index();
            $table->string('body_style')->nullable()->index();
            $table->string('trim_level')->nullable()->index();
            $table->string('color')->nullable()->index();
            $table->enum('car_group', ['pool', 'assigned'])->default('pool')->index();
            $table->string('car_number')->unique();
            $table->string('chassis')->nullable()->index();
            $table->string('odometer')->nullable()->index();
            $table->string('engine_capacity')->nullable()->index();
            $table->string('fuel_type')->nullable()->index();
            $table->string('tank_size')->nullable()->index();
            $table->string('car_cost')->nullable()->index();
            $table->date('purchase_date')->nullable()->index();
            $table->string('condition')->nullable()->index();
            $table->string('dvla_code')->nullable()->index();
            $table->date('dvla_expiry')->nullable()->index();
            $table->date('road_worthy_start_date')->nullable()->index();
            $table->date('road_worthy_expiry_date')->nullable()->index();
            $table->string('status', 100)->nullable()->index();
            $table->string('comment')->nullable()->index();
            $table->date('insurance_start_date')->nullable()->index();
            $table->date('insurance_expiry')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('cars');
    }
}
