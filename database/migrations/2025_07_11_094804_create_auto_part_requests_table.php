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
        Schema::create('auto_part_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_part_id');
            $table->foreign('auto_part_id')->references('id')->on('auto_parts')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('cars');
            $table->decimal('cost')->default(0);
            $table->string('request_type');
            $table->integer('qnt_requested');
            $table->string('reason_for_request');            
            $table->integer('qnt_approved');
            $table->string('status');
            $table->string('auth_by');
            $table->string('reason_for_decline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_part_requests');
    }
};
