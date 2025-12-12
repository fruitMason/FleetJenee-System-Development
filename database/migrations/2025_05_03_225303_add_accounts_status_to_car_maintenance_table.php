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
        Schema::table('car_maintenances', function (Blueprint $table) {
            $table->string('fin_status');
            $table->date('fin_date');
            $table->string('fin_user')->nullable();
            $table->text('fin_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_maintenances', function (Blueprint $table) {
            //
        });
    }
};
