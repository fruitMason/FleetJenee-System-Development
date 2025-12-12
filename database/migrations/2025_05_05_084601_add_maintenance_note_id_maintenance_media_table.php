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
        Schema::table('car_maintenance_media', function (Blueprint $table) {
            $table->unsignedBigInteger('car_maintenance_note_id');
            $table->foreign('car_maintenance_note_id')->references('id')->on('car_maintenance_notes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_maintenance_media', function (Blueprint $table) {
            //
        });
    }
};
