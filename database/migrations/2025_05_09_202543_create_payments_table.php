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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');   
            $table->unsignedBigInteger('payment_request_id');     
            $table->foreign('payment_request_id')->references('id')->on('payment_requests');                     
            $table->date('payment_date');         
            $table->decimal('amount_paid', 8, 2);           
            $table->string('payment_mode');
            $table->string('payment_reference')->nullable();
            $table->string('narration')->nullable();
            $table->string('payment_status');    
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
