<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccidentReportMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accident_report_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('accident_report_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('description')->nullable();
            $table->string('path')->nullable()->index();
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
        Schema::dropIfExists('accident_report_media');
    }
}
