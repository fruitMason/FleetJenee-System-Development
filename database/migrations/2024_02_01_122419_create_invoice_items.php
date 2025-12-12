<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id')->nullable()->index();
            $table->boolean('taxed')->default(false)->nullable();
            $table->boolean('is_cst')->default(false)->nullable();
            $table->boolean('is_vat_flat')->default(false)->nullable();
            $table->boolean('is_vat_standard')->default(false)->nullable();
            $table->string('total')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('selected_taxes')->nullable();
            $table->unsignedBigInteger('item_id')->nullable()->index();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('item_type')->nullable();
            $table->string('description')->nullable();
            $table->string('vat')->nullable();
            $table->string('nhil')->nullable();
            $table->string('getfund')->nullable();
            $table->string('cst')->nullable();
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
        Schema::dropIfExists('invoice_items');
    }
}
