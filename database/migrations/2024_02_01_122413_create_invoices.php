<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable()->index();
            $table->string('invoice_number_type')->index();
            $table->string('invoice_number')->unique()->index();
            $table->date('due_date')->nullable();
            $table->string('reference')->nullable();
            $table->string('message')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('net_total')->nullable();
            $table->string('tax_total')->nullable();
            $table->string('status')->default('pending')->nullable();
            $table->string('nhil_total')->nullable();
            $table->string('cst_total')->nullable();
            $table->string('getfund_total')->nullable();
            $table->string('vat_total')->nullable();
            $table->string('covid_total')->nullable();
            $table->string('vat_flat_total')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('edited_by')->nullable()->index();
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
        Schema::dropIfExists('invoices');
    }
}
