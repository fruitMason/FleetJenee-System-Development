<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELogActivityMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_log_activity_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('e_log_activity_id')->nullable()->index();
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
        Schema::dropIfExists('e_log_activity_media');
    }
}
