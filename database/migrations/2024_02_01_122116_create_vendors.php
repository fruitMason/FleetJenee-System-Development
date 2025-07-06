<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('email')->unique()->index();
            $table->string('password')->nullable();
            $table->string('phone_number', 20)->nullable()->index();
            $table->string('role', 100)->nullable()->index();
            $table->unsignedBigInteger('region_id')->nullable()->index();
            $table->string('status', 100)->nullable()->index();
            $table->string('address')->nullable();
            $table->string('service_type')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
