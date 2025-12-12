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
        Schema::create('auto_part_purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_part_id');
            $table->foreign('auto_part_id')->references('id')->on('auto_parts')->cascadeOnDelete();
            $table->decimal('cost')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_part_purchase_histories');
    }
};
