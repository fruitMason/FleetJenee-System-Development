<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('car_requests', function (Blueprint $table) {
           $table->string('trip_status')->default('pending');
           $table->unsignedBigInteger('car_id')->nullable();
           $table->foreign('car_id')->references('id')->on('cars');
           $table->string('auth_by')->default('0');
           $table->text('auth_comment')->nullable();
           $table->dateTime('start_time')->useCurrent();
           $table->dateTime('end_time')->useCurrent();
           $table->decimal('start_odometer')->default(0);
           $table->decimal('end_odometer')->default(0);
           $table->string('start_location')->default('N/A');
           $table->string('end_location')->default('N/A');
           $table->string('start_comment')->default('N/A');
           $table->string('end_comment')->default('N/A');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_requests', function (Blueprint $table) {
            //
        });
    }
};
