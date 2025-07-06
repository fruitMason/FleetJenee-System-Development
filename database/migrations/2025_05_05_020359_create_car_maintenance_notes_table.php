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
        Schema::create('car_maintenance_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('car_maintenance_id');
            $table->foreign('car_maintenance_id')->references('id')->on('car_maintenances')->cascadeOnDelete();
            $table->string('status');   
            $table->text('receipt_comment');
            $table->date('receipt_date');   
            $table->text('user_email');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_maintenance_notes');
    }
};
