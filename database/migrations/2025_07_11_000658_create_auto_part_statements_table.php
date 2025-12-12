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
        Schema::create('auto_part_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_part_id');
            $table->foreign('auto_part_id')->references('id')->on('auto_parts')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('stock_in');
            $table->integer('stock_out');
            $table->string('trans_type'); //stockin, use/damage
            $table->string('narration');
            $table->string('trans_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_part_statements');
    }
};
