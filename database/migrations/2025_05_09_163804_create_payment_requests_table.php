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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');   
            $table->unsignedBigInteger('car_id')->nullable()->index(); 
            $table->foreign('car_id')->references('id')->on('cars');     
            $table->date('request_date');      
            $table->string('payment_type');
            $table->string('description');
            $table->decimal('amount');
            $table->decimal('amount_paid');
            $table->string('status');
            $table->timestamp('date_paid')->nullable();
            $table->unsignedBigInteger('for_user_id')->nullable()->index(); 
            $table->string('car_assigned')->nullable();  
            $table->string('invoice_no');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
